<?php

namespace Tests\Feature;

use App\Models\Esquela;
use App\Models\Solicitud;
use Database\Seeders\DemoGoesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FineBlockRecoveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoGoesSeeder::class);
    }

    public function test_bloqueo_por_esquela_pendiente_y_recuperacion_en_linea(): void
    {
        // 1. Intento de inicio con esquela pendiente
        $responsePost1 = $this->post(route('tramite.identificacion.post'), [
            'tipo_documento' => 'DUI',
            'numero_documento' => '05123456-7',
            'correo' => 'ana.gomez@hotmail.com',
            'telefono' => '7123-4567',
        ]);

        $responsePost1->assertSessionHas('recuperacion_esquela');

        // 2. Comprobar que en la vista se presenta la alerta de recuperación
        $responseGet1 = $this->get(route('tramite.identificacion'));
        $responseGet1->assertStatus(200);
        $responseGet1->assertSee('VMT-2026-88319');
        $responseGet1->assertSee('34.29');
        $responseGet1->assertSee('Pagar Esquela en Línea Ahora');

        // 3. Solventar la esquela en línea mediante la acción de recuperación
        $esquela = Esquela::where('numero_esquela', 'VMT-2026-88319')->first();
        $this->assertNotNull($esquela);
        $this->assertSame('PENDIENTE', $esquela->estado);

        $responsePagar = $this->post(route('tramite.pagar-esquela'), [
            'esquela_id' => $esquela->id,
        ]);

        $responsePagar->assertSessionHas('esquela_pagada_exito');
        $esquela->refresh();
        $this->assertSame('PAGADA', $esquela->estado);

        // 4. Revalidar solvencia: Ahora debe permitir continuar
        $responsePost2 = $this->post(route('tramite.identificacion.post'), [
            'tipo_documento' => 'DUI',
            'numero_documento' => '05123456-7',
            'correo' => 'ana.gomez@hotmail.com',
            'telefono' => '7123-4567',
        ]);

        $responsePost2->assertRedirect(route('tramite.datos-y-pago'));

        // 5. Verificar que como su licencia estaba vencida, incluye la mora de $5.71
        $solicitud = Solicitud::where('correo_contacto', 'ana.gomez@hotmail.com')->latest()->first();
        $this->assertNotNull($solicitud);
        $this->assertEquals(5.71, $solicitud->monto_mora);
        $this->assertEquals(61.92, $solicitud->monto_total);
    }
}
