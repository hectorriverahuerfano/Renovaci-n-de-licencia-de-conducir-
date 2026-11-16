<?php

namespace Tests\Unit;

use App\Enums\ClaseLicencia;
use App\Enums\TipoDocumento;
use App\Models\CiudadanoDemo;
use App\Models\Licencia;
use App\Models\Solicitud;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentService();
    }

    public function test_calcula_aranceles_sin_mora_para_licencia_en_mes_de_vencimiento(): void
    {
        $ciudadano = CiudadanoDemo::create([
            'tipo_documento' => TipoDocumento::DUI,
            'numero_documento' => '02489123-4',
            'nombres' => 'Carlos',
            'apellidos' => 'Monterrosa',
            'fecha_nacimiento' => Carbon::create(1990, 5, 12),
        ]);

        $licencia = Licencia::create([
            'ciudadano_id' => $ciudadano->id,
            'numero_licencia' => '0614-120590-102-3',
            'clase' => ClaseLicencia::PARTICULAR,
            'fecha_emision' => Carbon::now()->subYears(5),
            'fecha_vencimiento' => Carbon::now()->endOfMonth(), // Mes en curso
            'estado' => 'VIGENTE',
        ]);

        $fees = $this->service->calculateFees($licencia);

        $this->assertEquals(52.82, $fees['tramite']);
        $this->assertEquals(3.39, $fees['examen_visual']);
        $this->assertFalse($fees['aplica_mora']);
        $this->assertEquals(0.00, $fees['mora']);
        $this->assertEquals(56.21, $fees['total']);
    }

    public function test_aplica_recargo_de_mora_de_cinco_dolares_setenta_y_uno_para_licencia_vencida(): void
    {
        $ciudadano = CiudadanoDemo::create([
            'tipo_documento' => TipoDocumento::DUI,
            'numero_documento' => '05123456-7',
            'nombres' => 'Ana',
            'apellidos' => 'Gómez',
            'fecha_nacimiento' => Carbon::create(1988, 3, 8),
        ]);

        $licencia = Licencia::create([
            'ciudadano_id' => $ciudadano->id,
            'numero_licencia' => '0614-030888-104-1',
            'clase' => ClaseLicencia::PARTICULAR,
            'fecha_emision' => Carbon::now()->subYears(5)->subMonths(3),
            'fecha_vencimiento' => Carbon::now()->subMonths(2), // Vencida hace 2 meses
            'estado' => 'VENCIDA',
        ]);

        $fees = $this->service->calculateFees($licencia);

        $this->assertTrue($fees['aplica_mora']);
        $this->assertEquals(5.71, $fees['mora']);
        $this->assertEquals(61.92, $fees['total']);
    }

    public function test_genera_mandamiento_npe_con_exactamente_30_digitos(): void
    {
        $ciudadano = CiudadanoDemo::create([
            'tipo_documento' => TipoDocumento::DUI,
            'numero_documento' => '02489123-4',
            'nombres' => 'Carlos',
            'apellidos' => 'Monterrosa',
            'fecha_nacimiento' => Carbon::create(1990, 5, 12),
        ]);

        $solicitud = Solicitud::create([
            'codigo_solicitud' => 'GOES-RN-20261116-TEST',
            'ciudadano_id' => $ciudadano->id,
            'clase_tramite' => 'PARTICULAR',
            'monto_tramite' => 52.82,
            'monto_examen_visual' => 3.39,
            'monto_mora' => 0.00,
            'monto_total' => 56.21,
            'paso_actual' => 3,
        ]);

        $pago = $this->service->generateNpePayment($solicitud);

        $this->assertNotNull($pago->npe);
        $this->assertSame(30, strlen($pago->npe));
        $this->assertSame('MANDAMIENTO_NPE', $pago->metodo_pago);
        $this->assertSame('PENDIENTE', $pago->estado->value);
    }
}
