<?php

namespace Tests\Unit;

use App\Services\QrCodeService;
use PHPUnit\Framework\TestCase;

class QrCodeServiceTest extends TestCase
{
    public function test_genera_svg_valido_con_etiquetas_vectoriales_y_accesibilidad(): void
    {
        $service = new QrCodeService();
        $svg = $service->generateSvg('GOES-RN-20261116-8921', 200);

        $this->assertStringStartsWith('<svg', trim($svg));
        $this->assertStringEndsWith('</svg>', trim($svg));
        $this->assertStringContainsString('viewBox=', $svg);
        $this->assertStringContainsString('role="img"', $svg);
        $this->assertStringContainsString('GOES-RN-20261116-8921', $svg);
        $this->assertStringContainsString('<rect', $svg);
    }
}
