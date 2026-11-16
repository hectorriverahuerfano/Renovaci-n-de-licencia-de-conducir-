<?php

namespace App\Http\Requests;

use App\Rules\DuiRule;
use Illuminate\Foundation\Http\FormRequest;

class IdentifyCitizenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'tipo_documento' => ['required', 'string', 'in:DUI,RESIDENTE_TEMPORAL,RESIDENTE_DEFINITIVO,DIPLOMATICO'],
            'numero_documento' => ['required', 'string', 'max:40'],
            'correo' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'simular_falla_api' => ['nullable', 'boolean'],
        ];

        if ($this->input('tipo_documento') === 'DUI') {
            $rules['numero_documento'][] = new DuiRule();
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tipo_documento.required' => 'Debe seleccionar el tipo de documento de identidad.',
            'numero_documento.required' => 'Ingrese el número de su documento de identidad.',
            'correo.email' => 'Ingrese una dirección de correo electrónico válida para enviarle su comprobante.',
        ];
    }
}
