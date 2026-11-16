<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'solicitud_id' => ['required', 'integer', 'exists:solicitudes,id'],
            'metodo_pago' => ['required', 'string', 'in:TARJETA,CHIVO_WALLET,TRANSFERENCIA_365,MANDAMIENTO_NPE'],
        ];

        if ($this->input('metodo_pago') === 'TARJETA') {
            $rules['numero_tarjeta'] = ['required', 'string', 'min:15', 'max:19'];
            $rules['titular_tarjeta'] = ['required', 'string', 'min:3', 'max:100'];
            $rules['expiracion'] = ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'];
            $rules['cvv'] = ['required', 'string', 'min:3', 'max:4'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'metodo_pago.required' => 'Seleccione una modalidad de pago.',
            'numero_tarjeta.required' => 'Ingrese el número de la tarjeta de débito o crédito.',
            'titular_tarjeta.required' => 'Ingrese el nombre del titular tal como figura en el plástico.',
            'expiracion.required' => 'Ingrese la fecha de expiración en formato MM/AA.',
            'cvv.required' => 'Ingrese el código de seguridad CVV.',
        ];
    }
}
