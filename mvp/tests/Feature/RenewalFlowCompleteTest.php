<?php

namespace Tests\Feature;

use App\Models\Solicitud;
use App\Models\Sucursal;
use Carbon\Carbon;
use Database\Seeders\DemoGoesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenewalFlowCompleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoGoesSeeder::class);
    }

    public function test_flujo_feliz_completo_de_5_pasos_salvadoreno_al_dia(): void
    {
        // -------------------------------------------------------------
        // Paso 1: Portal de Inicio y Requisitos
        // -------------------------------------------------------------
        $responsePaso1 = $this->get(route('inicio'));
        $responsePaso1->assertStatus(200);
        $responsePaso1->assertSee('Renueva tu Licencia de Conducir en Minutos');
        $responsePaso1->assertSee('US$ 56.21');
        $responsePaso1->assertSee('Particular');

        // -------------------------------------------------------------
        // Paso 2: Identificación y Solvencia VMT
        // -------------------------------------------------------------
        $responsePaso2Get = $this->get(route('tramite.identificacion'));
        $responsePaso2Get->assertStatus(200);

        $responsePaso2Post = $this->post(route('tramite.identificacion.post'), [
            'tipo_documento' => 'DUI',
            'numero_documento' => '02489123-4',
            'correo' => 'carlos.monterrosa@gmail.com',
            'telefono' => '7845-1234',
        ]);

        $responsePaso2Post->assertRedirect(route('tramite.datos-y-pago'));
        $this->assertDatabaseHas('solicitudes', [
            'correo_contacto' => 'carlos.monterrosa@gmail.com',
            'paso_actual' => 3,
        ]);

        $solicitud = Solicitud::latest()->first();
        $this->assertNotNull($solicitud);
        $this->assertEquals(56.21, $solicitud->monto_total);

        // -------------------------------------------------------------
        // Paso 3: Confirmación de Datos y Pago Digital
        // -------------------------------------------------------------
        $responsePaso3Get = $this->withSession(['solicitud_id' => $solicitud->id])
            ->get(route('tramite.datos-y-pago'));
        $responsePaso3Get->assertStatus(200);
        $responsePaso3Get->assertSee('Carlos Ernesto Monterrosa Peña');
        $responsePaso3Get->assertSee('56.21');

        $responsePaso3Post = $this->post(route('tramite.pago.post'), [
            'solicitud_id' => $solicitud->id,
            'metodo_pago' => 'TARJETA',
            'titular_tarjeta' => 'CARLOS MONTERROSA',
            'numero_tarjeta' => '4000123456789010',
            'expiracion' => '12/28',
            'cvv' => '123',
        ]);

        $responsePaso3Post->assertRedirect(route('tramite.cita'));
        $this->assertDatabaseHas('pagos', [
            'solicitud_id' => $solicitud->id,
            'estado' => 'APROBADO',
            'monto' => 56.21,
        ]);

        // -------------------------------------------------------------
        // Paso 4: Agendamiento de Cita y Checklist
        // -------------------------------------------------------------
        $sucursal = Sucursal::where('codigo', 'CASCADAS')->first();

        $responsePaso4Get = $this->withSession(['solicitud_id' => $solicitud->id])
            ->get(route('tramite.cita'));
        $responsePaso4Get->assertStatus(200);
        $responsePaso4Get->assertSee('SERTRACEN Las Cascadas');

        $fechaCita = Carbon::now()->addDays(3)->format('Y-m-d');
        $responsePaso4Post = $this->post(route('tramite.cita.post'), [
            'solicitud_id' => $solicitud->id,
            'sucursal_id' => $sucursal->id,
            'fecha_cita' => $fechaCita,
            'hora_cita' => '09:15 AM',
            'tipo_atencion' => 'ESTANDAR_EXPRESS',
            'checklist_dui' => '1',
            'checklist_licencia' => '1',
            'checklist_lentes' => '0',
        ]);

        $responsePaso4Post->assertRedirect(route('tramite.comprobante', ['codigo' => $solicitud->codigo_solicitud]));

        // -------------------------------------------------------------
        // Paso 5: Comprobante Oficial, Pase QR y Timeline
        // -------------------------------------------------------------
        $responsePaso5Get = $this->get(route('tramite.comprobante', ['codigo' => $solicitud->codigo_solicitud]));
        $responsePaso5Get->assertStatus(200);
        $responsePaso5Get->assertSee($solicitud->codigo_solicitud);
        $responsePaso5Get->assertSee('Pase Oficial de Fila Express');
        $responsePaso5Get->assertSee('09:15 AM');
        $responsePaso5Get->assertSee('<svg', false); // Contiene el QR SVG
    }
}
