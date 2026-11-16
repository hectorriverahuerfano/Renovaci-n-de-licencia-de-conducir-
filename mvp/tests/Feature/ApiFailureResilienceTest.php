<?php

namespace Tests\Feature;

use Database\Seeders\DemoGoesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiFailureResilienceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoGoesSeeder::class);
    }

    public function test_falla_simulada_de_api_externa_es_capturada_con_alerta_de_recuperacion_sin_stack_trace(): void
    {
        $response = $this->post(route('tramite.identificacion.post'), [
            'tipo_documento' => 'DUI',
            'numero_documento' => '09876543-2', // Ciudadano configurado con es_simulacion_falla = true
        ]);

        $response->assertSessionHas('error_api');

        $responseGet = $this->get(route('tramite.identificacion'));
        $responseGet->assertStatus(200);
        $responseGet->assertSee('Interrupción temporal del Sistema de Consultas del VMT');
        $responseGet->assertSee('Reintentar Consulta VMT');
        $responseGet->assertSee('Solicitar Atención Asistida');
        $responseGet->assertDontSee('Exception');
        $responseGet->assertDontSee('Stack trace');
    }
}
