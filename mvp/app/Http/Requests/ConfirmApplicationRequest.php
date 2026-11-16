<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'solicitud_id' => ['required', 'integer', 'exists:solicitudes,id'],
            'correo_contacto' => ['required', 'email', 'max:150'],
            'telefono_contacto' => ['required', 'string', 'max:25'],
            'confirmacion_datos' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'solicitud_id.required' => 'Falta el identificador de la solicitud en curso.',
            'correo_contacto.required' => 'El correo electrónico de contacto es obligatorio para enviarle su comprobante oficial.',
            'telefono_contacto.required' => 'El número telefónico es obligatorio para enviarle recordatorios SMS.',
            'confirmacion_datos.accepted' => 'Debe confirmar bajo juramento que los datos mostrados son correctos.',
        ];
    }
}
