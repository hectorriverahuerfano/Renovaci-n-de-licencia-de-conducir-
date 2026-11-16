<?php

namespace Database\Seeders;

use App\Enums\ClaseLicencia;
use App\Enums\TipoDocumento;
use App\Models\CiudadanoDemo;
use App\Models\Esquela;
use App\Models\Licencia;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoGoesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sucursales de SERTRACEN
        $sucursales = [
            [
                'codigo' => 'CASCADAS',
                'nombre' => 'SERTRACEN Las Cascadas (Antiguo Cuscatlán)',
                'direccion' => 'Centro Comercial Las Cascadas, Local 120, Antiguo Cuscatlán, La Libertad',
                'departamento' => 'La Libertad',
                'es_recomendada' => true,
                'es_prioritaria' => false,
                'capacidad_por_hora' => 30,
                'activo' => true,
            ],
            [
                'codigo' => 'CENTRAL_SS',
                'nombre' => 'SERTRACEN Central San Salvador (Autopista Sur)',
                'direccion' => 'Boulevard Los Próceres y Autopista Sur, San Salvador',
                'departamento' => 'San Salvador',
                'es_recomendada' => false,
                'es_prioritaria' => false,
                'capacidad_por_hora' => 25,
                'activo' => true,
            ],
            [
                'codigo' => 'SANTA_ANA',
                'nombre' => 'SERTRACEN Santa Ana (Plaza Kristal)',
                'direccion' => 'Plaza Kristal, Final 25 Calle Poniente, Santa Ana',
                'departamento' => 'Santa Ana',
                'es_recomendada' => false,
                'es_prioritaria' => false,
                'capacidad_por_hora' => 20,
                'activo' => true,
            ],
            [
                'codigo' => 'SAN_MIGUEL',
                'nombre' => 'SERTRACEN San Miguel (Metrocentro)',
                'direccion' => 'Centro Comercial Metrocentro San Miguel, 8ª Calle Oriente',
                'departamento' => 'San Miguel',
                'es_recomendada' => false,
                'es_prioritaria' => false,
                'capacidad_por_hora' => 20,
                'activo' => true,
            ],
            [
                'codigo' => 'PRIORITARIA',
                'nombre' => 'Ventanilla de Atención Prioritaria (Adulto Mayor y Movilidad Reducida)',
                'direccion' => 'Todas las sucursales SERTRACEN (Módulo Inclusivo Especial)',
                'departamento' => 'Nacional',
                'es_recomendada' => false,
                'es_prioritaria' => true,
                'capacidad_por_hora' => 15,
                'activo' => true,
            ],
        ];

        foreach ($sucursales as $suc) {
            Sucursal::updateOrCreate(
                ['codigo' => $suc['codigo']],
                $suc
            );
        }

        $now = Carbon::now();

        // -------------------------------------------------------------
        // Escenario 1: Flujo Feliz (Salvadoreño al día)
        // -------------------------------------------------------------
        $carlos = CiudadanoDemo::updateOrCreate(
            ['numero_documento' => '02489123-4'],
            [
                'tipo_documento' => TipoDocumento::DUI,
                'nombres' => 'Carlos Ernesto',
                'apellidos' => 'Monterrosa Peña',
                'fecha_nacimiento' => Carbon::create(1990, 5, 12),
                'correo' => 'carlos.monterrosa@gmail.com',
                'telefono' => '7845-1234',
                'vigencia_documento_hasta' => Carbon::now()->addYears(6),
                'es_simulacion_falla' => false,
            ]
        );

        Licencia::updateOrCreate(
            ['numero_licencia' => '0614-120590-102-3'],
            [
                'ciudadano_id' => $carlos->id,
                'clase' => ClaseLicencia::PARTICULAR,
                'fecha_emision' => Carbon::now()->subYears(5)->addDays(10),
                'fecha_vencimiento' => Carbon::now()->endOfMonth(), // En mes de vencimiento (sin mora)
                'estado' => 'VIGENTE',
                'donante_organos' => true,
                'tipo_sangre' => 'O+',
            ]
        );

        // Asegurar 0 esquelas pendientes para Carlos
        Esquela::where('ciudadano_id', $carlos->id)->delete();

        // -------------------------------------------------------------
        // Escenario 2: Bloqueo Recuperable (Multa Pendiente + Licencia Vencida)
        // -------------------------------------------------------------
        $ana = CiudadanoDemo::updateOrCreate(
            ['numero_documento' => '05123456-7'],
            [
                'tipo_documento' => TipoDocumento::DUI,
                'nombres' => 'Ana Patricia',
                'apellidos' => 'Gómez Rivas',
                'fecha_nacimiento' => Carbon::create(1988, 3, 8),
                'correo' => 'ana.gomez@hotmail.com',
                'telefono' => '7123-4567',
                'vigencia_documento_hasta' => Carbon::now()->addYears(4),
                'es_simulacion_falla' => false,
            ]
        );

        Licencia::updateOrCreate(
            ['numero_licencia' => '0614-030888-104-1'],
            [
                'ciudadano_id' => $ana->id,
                'clase' => ClaseLicencia::PARTICULAR,
                'fecha_emision' => Carbon::now()->subYears(5)->subMonths(3),
                'fecha_vencimiento' => Carbon::now()->subMonths(2), // Vencida hace 2 meses (aplica mora $5.71)
                'estado' => 'VENCIDA',
                'donante_organos' => false,
                'tipo_sangre' => 'A+',
            ]
        );

        Esquela::updateOrCreate(
            ['numero_esquela' => 'VMT-2026-88319'],
            [
                'ciudadano_id' => $ana->id,
                'infraccion_codigo' => 'Art. 117 VMT',
                'tipo_falta' => 'GRAVE',
                'descripcion' => 'Estacionar en zona prohibida o eje preferencial debidamente señalizado',
                'departamento' => 'San Salvador',
                'fecha_infraccion' => Carbon::now()->subMonths(1),
                'monto' => 34.29,
                'estado' => 'PENDIENTE',
                'fecha_pago' => null,
                'transaccion_pago_ref' => null,
            ]
        );

        // -------------------------------------------------------------
        // Escenario 3: Falla Simulada de API Externa (Resiliencia VMT)
        // -------------------------------------------------------------
        $roberto = CiudadanoDemo::updateOrCreate(
            ['numero_documento' => '09876543-2'],
            [
                'tipo_documento' => TipoDocumento::DUI,
                'nombres' => 'Roberto Alexander',
                'apellidos' => 'Morales Cañas',
                'fecha_nacimiento' => Carbon::create(1985, 7, 25),
                'correo' => 'roberto.morales@gmail.com',
                'telefono' => '7456-7890',
                'vigencia_documento_hasta' => Carbon::now()->addYears(5),
                'es_simulacion_falla' => true, // Provoca simulación de caída
            ]
        );

        Licencia::updateOrCreate(
            ['numero_licencia' => '0614-250785-103-8'],
            [
                'ciudadano_id' => $roberto->id,
                'clase' => ClaseLicencia::PARTICULAR,
                'fecha_emision' => Carbon::now()->subYears(5),
                'fecha_vencimiento' => Carbon::now()->addDays(15),
                'estado' => 'VIGENTE',
                'donante_organos' => true,
                'tipo_sangre' => 'B+',
            ]
        );

        // -------------------------------------------------------------
        // Escenario 4A: Extranjero no elegible (< 3 meses carnet temporal)
        // -------------------------------------------------------------
        $hans = CiudadanoDemo::updateOrCreate(
            ['numero_documento' => 'RES-TMP-2026-01'],
            [
                'tipo_documento' => TipoDocumento::RESIDENTE_TEMPORAL,
                'nombres' => 'Hans Peter',
                'apellidos' => 'Müller Schneider',
                'fecha_nacimiento' => Carbon::create(1975, 4, 15),
                'correo' => 'hans.muller@outlook.com',
                'telefono' => '7999-8877',
                'vigencia_documento_hasta' => Carbon::now()->addDays(40), // Menos de 3 meses de vigencia restante
                'es_simulacion_falla' => false,
            ]
        );

        Licencia::updateOrCreate(
            ['numero_licencia' => '0614-010170-999-1'],
            [
                'ciudadano_id' => $hans->id,
                'clase' => ClaseLicencia::PARTICULAR,
                'fecha_emision' => Carbon::now()->subYears(5),
                'fecha_vencimiento' => Carbon::now()->addDays(20),
                'estado' => 'VIGENTE',
                'donante_organos' => false,
                'tipo_sangre' => 'O-',
            ]
        );

        // -------------------------------------------------------------
        // Escenario 4B: Canje Juvenil a Adulto (cumplió 18 años)
        // -------------------------------------------------------------
        $diego = CiudadanoDemo::updateOrCreate(
            ['numero_documento' => '07654321-0'],
            [
                'tipo_documento' => TipoDocumento::DUI,
                'nombres' => 'Diego Alejandro',
                'apellidos' => 'Navas Guardado',
                'fecha_nacimiento' => Carbon::now()->subYears(18)->subMonths(1), // 18 años cumplidos
                'correo' => 'diego.navas@gmail.com',
                'telefono' => '7654-3210',
                'vigencia_documento_hasta' => Carbon::now()->addYears(7),
                'es_simulacion_falla' => false,
            ]
        );

        Licencia::updateOrCreate(
            ['numero_licencia' => 'JUV-2026-18'],
            [
                'ciudadano_id' => $diego->id,
                'clase' => ClaseLicencia::JUVENIL_PARTICULAR,
                'fecha_emision' => Carbon::now()->subYear(),
                'fecha_vencimiento' => Carbon::now()->subMonths(1),
                'estado' => 'VENCIDA',
                'donante_organos' => true,
                'tipo_sangre' => 'O+',
            ]
        );

        // -------------------------------------------------------------
        // Escenario 4C: Extranjero Elegible (Residente Definitivo)
        // -------------------------------------------------------------
        $jean = CiudadanoDemo::updateOrCreate(
            ['numero_documento' => 'RES-DEF-2024-99'],
            [
                'tipo_documento' => TipoDocumento::RESIDENTE_DEFINITIVO,
                'nombres' => 'Jean-Luc',
                'apellidos' => 'Dupont',
                'fecha_nacimiento' => Carbon::create(1982, 11, 20),
                'correo' => 'jeanluc.dupont@embassy.fr',
                'telefono' => '7222-3344',
                'vigencia_documento_hasta' => Carbon::now()->addYears(3),
                'es_simulacion_falla' => false,
            ]
        );

        Licencia::updateOrCreate(
            ['numero_licencia' => '9901-010185-101-1'],
            [
                'ciudadano_id' => $jean->id,
                'clase' => ClaseLicencia::PARTICULAR,
                'fecha_emision' => Carbon::now()->subYears(5),
                'fecha_vencimiento' => Carbon::now()->endOfMonth(),
                'estado' => 'VIGENTE',
                'donante_organos' => false,
                'tipo_sangre' => 'A+',
            ]
        );
    }
}
