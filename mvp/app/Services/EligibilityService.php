<?php

namespace App\Services;

use App\Enums\ClaseLicencia;
use App\Enums\TipoDocumento;
use App\Models\CiudadanoDemo;
use App\Models\Licencia;
use Carbon\Carbon;

class EligibilityService
{
    /**
     * Evalúa las condiciones legales y reglamentarias para renovar la licencia en El Salvador.
     * Retorna array con:
     * [
     *   'eligible' => bool,
     *   'code' => string, // OK, RESIDENTE_VIGENCIA_INSUFICIENTE, CANJE_JUVENIL_REQUERIDO, LICENCIA_SUSPENDIDA, NO_POSEE_LICENCIA
     *   'message' => string,
     *   'legal_reference' => ?string
     * ]
     */
    public function checkEligibility(CiudadanoDemo $ciudadano, ?Licencia $licencia, ?string $claseSolicitada = null): array
    {
        // 1. Verificar si posee licencia previa
        if (! $licencia) {
            return [
                'eligible' => false,
                'code' => 'NO_POSEE_LICENCIA',
                'message' => 'No se encontró un registro previo de licencia de conducir asociada a este documento. Si es su primera vez, debe iniciar el trámite de Primera Licencia.',
                'legal_reference' => 'Ley de Transporte Terrestre, Tránsito y Seguridad Vial — Art. 65',
            ];
        }

        // 2. Verificar estado de la licencia (suspensiones judiciales o administrativas)
        if ($licencia->estado === 'SUSPENDIDA') {
            return [
                'eligible' => false,
                'code' => 'LICENCIA_SUSPENDIDA',
                'message' => 'Su licencia de conducir se encuentra administrativamente suspendida por resolución del VMT. Debe comparecer a la Unidad Jurídica.',
                'legal_reference' => 'Reglamento General de Tránsito y Seguridad Vial — Art. 120',
            ];
        }

        // 3. Regla para Extranjeros con Carnet de Residente Temporal:
        // Según requisitos oficiales GOES/VMT: Debe contar con más de 3 meses de vigencia restante.
        if ($ciudadano->tipo_documento === TipoDocumento::RESIDENTE_TEMPORAL) {
            if ($ciudadano->vigencia_documento_hasta) {
                $mesesRestantes = Carbon::now()->diffInDays($ciudadano->vigencia_documento_hasta, false) / 30;
                if ($mesesRestantes < 3) {
                    return [
                        'eligible' => false,
                        'code' => 'RESIDENTE_VIGENCIA_INSUFICIENTE',
                        'message' => 'Su Carnet de Residente Temporal posee una vigencia restante menor a tres (3) meses. La normativa legal exige renovar primero su condición migratoria ante la Dirección General de Migración y Extranjería.',
                        'legal_reference' => 'Requisitos Oficiales SERTRACEN / VMT — Condición de Residencia Temporal',
                    ];
                }
            }
        }

        // 4. Regla para Licencias Juveniles:
        // Si la licencia previa es Juvenil y el titular ya cumplió 18 años, no procede renovación juvenil sino trámite de Canje a Licencia de Mayor de Edad (Particular/Moto).
        $esLicenciaJuvenil = in_array($licencia->clase, [ClaseLicencia::JUVENIL_PARTICULAR, ClaseLicencia::JUVENIL_MOTO]);
        $edad = $ciudadano->fecha_nacimiento->age;

        if ($esLicenciaJuvenil && $edad >= 18) {
            return [
                'eligible' => false,
                'code' => 'CANJE_JUVENIL_REQUERIDO',
                'message' => 'Al haber alcanzado la mayoría de edad (18 años), su licencia juvenil no admite renovación directa. Debe realizar el trámite de Canje a Licencia Particular / Adulto presentando su DUI original.',
                'legal_reference' => 'Ley de Transporte Terrestre — Regulaciones para conductores noveles y mayores de edad',
            ];
        }

        return [
            'eligible' => true,
            'code' => 'OK',
            'message' => 'El ciudadano cumple con todas las condiciones de elegibilidad legal para renovar su licencia.',
            'legal_reference' => null,
        ];
    }
}
