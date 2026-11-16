<?php

namespace Tests\Feature;

use App\Models\CiudadanoDemo;
use App\Models\Solicitud;
use Database\Seeders\DemoGoesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingAndAssistedHelpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoGoesSeeder::class);
    }

    public function test_seguimiento_de_tramite_por_codigo_oficial(): void
    {
        $ciudadano = CiudadanoDemo::where('numero_documento', '02489123-4')->first();

        $solicitud = Solicitud::create([
            'codigo_solicitud' => 'GOES-RN-20261116-8921',
            'ciudadano_id' => $ciudadano->id,
            'clase_tramite' => 'PARTICULAR',
            'estado' => 'CITA_AGENDADA',
            'monto_total' => 56.21,
            'paso_actual' => 5,
        ]);

        $response = $this->post(route('seguimiento.buscar'), [
            'criterio' => 'GOES-RN-20261116-8921',
        ]);

        $response->assertStatus(200);
        $response->assertSee('GOES-RN-20261116-8921');
        $response->assertSee('Carlos Ernesto Monterrosa Peña');
        $response->assertSee('56.21');
    }

    public function test_seguimiento_con_criterio_inexistente_muestra_alerta_amigable(): void
    {
        $response = $this->post(route('seguimiento.buscar'), [
            'criterio' => 'CODIGO-INEXISTENTE-999',
        ]);

        $response->assertStatus(200);
        $response->assertSee('No se encontró ningún trámite registrado');
    }

    public function test_registro_de_atencion_asistida_genera_ticket_en_base_de_datos(): void
    {
        $response = $this->post(route('atencion-asistida.store'), [
            'nombre_contacto' => 'Beatriz Elena Guardado',
            'telefono_contacto' => '7890-4321',
            'correo_contacto' => 'beatriz@ejemplo.com',
            'motivo' => 'DIFICULTAD_DIGITAL',
            'descripcion' => 'Requiero apoyo para verificar si mi tarjeta de débito fue debitada.',
        ]);

        $response->assertSessionHas('ayuda_registrada_exito');

        $this->assertDatabaseHas('notificaciones', [
            'canal' => 'WEB_ASISTIDA',
            'tipo' => 'ATENCION_ASISTIDA',
            'estado' => 'REGISTRADO',
        ]);
    }

    public function test_encabezados_de_seguridad_estan_presentes(): void
    {
        $response = $this->get(route('inicio'));

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
