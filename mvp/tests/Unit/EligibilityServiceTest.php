<?php

namespace Tests\Unit;

use App\Enums\ClaseLicencia;
use App\Enums\TipoDocumento;
use App\Models\CiudadanoDemo;
use App\Models\Licencia;
use App\Services\EligibilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EligibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EligibilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EligibilityService();
    }

    public function test_ciudadano_sin_licencia_previa_no_es_elegible_para_renovacion(): void
    {
        $ciudadano = CiudadanoDemo::create([
            'tipo_documento' => TipoDocumento::DUI,
            'numero_documento' => '02489123-4',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'fecha_nacimiento' => Carbon::create(1995, 1, 1),
        ]);

        $result = $this->service->checkEligibility($ciudadano, null);

        $this->assertFalse($result['eligible']);
        $this->assertSame('NO_POSEE_LICENCIA', $result['code']);
    }

    public function test_licencia_suspendida_es_bloqueada_con_referencia_legal(): void
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
            'fecha_vencimiento' => Carbon::now()->addDays(10),
            'estado' => 'SUSPENDIDA',
        ]);

        $result = $this->service->checkEligibility($ciudadano, $licencia);

        $this->assertFalse($result['eligible']);
        $this->assertSame('LICENCIA_SUSPENDIDA', $result['code']);
    }

    public function test_extranjero_con_carnet_temporal_menor_a_3_meses_es_bloqueado(): void
    {
        $extranjero = CiudadanoDemo::create([
            'tipo_documento' => TipoDocumento::RESIDENTE_TEMPORAL,
            'numero_documento' => 'RES-TMP-2026-01',
            'nombres' => 'Hans',
            'apellidos' => 'Müller',
            'fecha_nacimiento' => Carbon::create(1980, 1, 1),
            'vigencia_documento_hasta' => Carbon::now()->addDays(35), // < 3 meses
        ]);

        $licencia = Licencia::create([
            'ciudadano_id' => $extranjero->id,
            'numero_licencia' => '0614-010170-999-1',
            'clase' => ClaseLicencia::PARTICULAR,
            'fecha_emision' => Carbon::now()->subYears(5),
            'fecha_vencimiento' => Carbon::now()->addDays(20),
            'estado' => 'VIGENTE',
        ]);

        $result = $this->service->checkEligibility($extranjero, $licencia);

        $this->assertFalse($result['eligible']);
        $this->assertSame('RESIDENTE_VIGENCIA_INSUFICIENTE', $result['code']);
    }

    public function test_licencia_juvenil_con_18_anios_cumplidos_requiere_canje_a_adulto(): void
    {
        $joven = CiudadanoDemo::create([
            'tipo_documento' => TipoDocumento::DUI,
            'numero_documento' => '07654321-0',
            'nombres' => 'Diego',
            'apellidos' => 'Navas',
            'fecha_nacimiento' => Carbon::now()->subYears(18)->subMonths(1), // 18 años cumplidos
        ]);

        $licenciaJuvenil = Licencia::create([
            'ciudadano_id' => $joven->id,
            'numero_licencia' => 'JUV-2026-18',
            'clase' => ClaseLicencia::JUVENIL_PARTICULAR,
            'fecha_emision' => Carbon::now()->subYear(),
            'fecha_vencimiento' => Carbon::now()->subDays(5),
            'estado' => 'VENCIDA',
        ]);

        $result = $this->service->checkEligibility($joven, $licenciaJuvenil);

        $this->assertFalse($result['eligible']);
        $this->assertSame('CANJE_JUVENIL_REQUERIDO', $result['code']);
    }

    public function test_ciudadano_salvadoreno_con_licencia_vigente_es_totalmente_elegible(): void
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
            'fecha_vencimiento' => Carbon::now()->addDays(15),
            'estado' => 'VIGENTE',
        ]);

        $result = $this->service->checkEligibility($ciudadano, $licencia);

        $this->assertTrue($result['eligible']);
        $this->assertSame('OK', $result['code']);
    }
}
