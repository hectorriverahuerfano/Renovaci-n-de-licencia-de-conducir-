<?php

namespace Tests\Unit;

use App\Rules\DuiRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DuiRuleTest extends TestCase
{
    public function test_acepta_duis_validos_con_formato_oficial(): void
    {
        $duisValidos = [
            '02489123-4',
            '05123456-7',
            '09876543-2',
            '07654321-0',
        ];

        foreach ($duisValidos as $dui) {
            $validator = Validator::make(
                ['dui' => $dui],
                ['dui' => ['required', new DuiRule()]]
            );

            $this->assertFalse($validator->fails(), "El DUI {$dui} debería ser válido.");
        }
    }

    public function test_rechaza_duis_con_formato_invalido(): void
    {
        $duisInvalidos = [
            '12345678', // sin guion
            '02489123-44', // dos digitos verificadores
            '0248912-4', // 7 dígitos
            'abcdefgh-1', // letras
            '',
        ];

        foreach ($duisInvalidos as $dui) {
            $validator = Validator::make(
                ['dui' => $dui],
                ['dui' => ['required', new DuiRule()]]
            );

            $this->assertTrue($validator->fails(), "El DUI '{$dui}' debería haber fallado la validación.");
        }
    }
}
