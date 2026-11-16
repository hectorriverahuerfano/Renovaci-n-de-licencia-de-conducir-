<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssistedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'solicitud_id' => ['nullable', 'integer', 'exists:solicitudes,id'],
            'nombre_contacto' => ['required', 'string', 'max:100'],
            'telefono_contacto' => ['required', 'string', 'max:25'],
            'correo_contacto' => ['nullable', 'email', 'max:150'],
            'motivo' => ['required', 'string', 'in:DIFICULTAD_DIGITAL,APELACION_ESQUELA,ERROR_SISTEMA,REPROGRAMACION,OTRO'],
            'descripcion' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_contacto.required' => 'Ingrese su nombre para que un agente oficial de atención ciudadana pueda contactarle.',
            'telefono_contacto.required' => 'Ingrese su número telefónico de contacto.',
            'motivo.required' => 'Seleccione el motivo de su solicitud de asistencia.',
            'descripcion.required' => 'Describa brevemente cómo podemos ayudarle.',
        ];
    }
}
