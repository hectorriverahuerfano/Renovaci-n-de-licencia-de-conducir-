<?php

namespace App\Services;

use App\Enums\PagoEstado;
use App\Enums\SolicitudEstado;
use App\Models\Licencia;
use App\Models\Pago;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentService
{
    public const TARIFA_TRAMITE = 52.82;
    public const TARIFA_EXAMEN_VISUAL = 3.39;
    public const TARIFA_MORA_EXTEMPORANEA = 5.71;

    /**
     * Calcula el desglose tarifario oficial según el estado de la licencia.
     */
    public function calculateFees(Licencia $licencia): array
    {
        $tramite = self::TARIFA_TRAMITE;
        $examen = self::TARIFA_EXAMEN_VISUAL;
        $aplicaMora = $licencia->aplicaMoraExtemporanea();
        $mora = $aplicaMora ? self::TARIFA_MORA_EXTEMPORANEA : 0.00;
        $total = $tramite + $examen + $mora;

        return [
            'tramite' => $tramite,
            'examen_visual' => $examen,
            'aplica_mora' => $aplicaMora,
            'mora' => $mora,
            'total' => round($total, 2),
        ];
    }

    /**
     * Procesa un pago digital (Tarjeta Débito/Crédito, Chivo Wallet, Transferencia 365).
     */
    public function processDigitalPayment(Solicitud $solicitud, string $metodo, array $extraData = []): Pago
    {
        $pago = Pago::create([
            'solicitud_id' => $solicitud->id,
            'referencia_transaccion' => 'PAY-GOES-' . strtoupper(Str::random(10)),
            'metodo_pago' => $metodo,
            'monto' => $solicitud->monto_total,
            'estado' => PagoEstado::APROBADO,
            'fecha_pago' => Carbon::now(),
            'detalles_pasarela' => array_merge([
                'autorizacion' => strtoupper(Str::random(8)),
                'pasarela' => 'PAGOS_GOB_SV_TLS_1_3',
                'timestamp' => Carbon::now()->toIso8601String(),
            ], $extraData),
        ]);

        $solicitud->update([
            'estado' => SolicitudEstado::PAGADA,
            'paso_actual' => 4,
        ]);

        return $pago;
    }

    /**
     * Genera un mandamiento de pago con NPE (Número de Pago Electrónico) de 30 dígitos
     * para liquidación en ventanillas bancarias o SERTRACEN.
     */
    public function generateNpePayment(Solicitud $solicitud): Pago
    {
        // Generar un NPE estructurado de 30 dígitos
        // Prefijo institucional (4 dig) + código trámite (6 dig) + monto formateado (6 dig) + correlativo (14 dig)
        $montoPadded = str_pad(str_replace('.', '', number_format($solicitud->monto_total, 2, '.', '')), 6, '0', STR_PAD_LEFT);
        $randomPart = str_pad((string) mt_rand(1000000000, 9999999999), 14, '0', STR_PAD_LEFT);
        $npe = '0614' . '102030' . $montoPadded . $randomPart; // 30 dígitos

        $pago = Pago::create([
            'solicitud_id' => $solicitud->id,
            'referencia_transaccion' => 'NPE-GOES-' . strtoupper(Str::random(8)),
            'metodo_pago' => 'MANDAMIENTO_NPE',
            'monto' => $solicitud->monto_total,
            'estado' => PagoEstado::PENDIENTE, // Pago en efectivo pendiente en banco
            'npe' => $npe,
            'npe_vencimiento' => Carbon::now()->addDays(8),
            'detalles_pasarela' => [
                'tipo' => 'MANDAMIENTO_EN_EFECTIVO',
                'canales_autorizados' => ['Banco Agrícola', 'Banco Cuscatlán', 'Banco Davivienda', 'Puntos SERTRACEN', 'Chivo Puntos'],
            ],
        ]);

        // Para fines del flujo digital, permitimos avanzar a agendar cita mientras el NPE esté emitido
        $solicitud->update([
            'estado' => SolicitudEstado::PAGADA,
            'paso_actual' => 4,
        ]);

        return $pago;
    }
}
