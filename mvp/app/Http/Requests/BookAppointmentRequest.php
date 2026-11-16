<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'solicitud_id' => ['required', 'integer', 'exists:solicitudes,id'],
            'sucursal_id' => ['required', 'integer', 'exists:sucursales,id'],
            'fecha_cita' => ['required', 'date', 'after_or_equal:today'],
            'hora_cita' => ['required', 'string'],
            'tipo_atencion' => ['required', 'string', 'in:ESTANDAR_EXPRESS,PRIORITARIA_ADULTO_MAYOR'],
            'checklist_dui' => ['accepted'],
            'checklist_licencia' => ['accepted'],
            'checklist_lentes' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sucursal_id.required' => 'Debe seleccionar una sucursal de SERTRACEN.',
            'fecha_cita.required' => 'Seleccione una fecha disponible para su cita.',
            'hora_cita.required' => 'Seleccione un turno horario disponible.',
            'checklist_dui.accepted' => 'Debe confirmar que llevará su documento de identidad original en físico el día de la cita.',
            'checklist_licencia.accepted' => 'Debe confirmar que llevará su licencia de conducir física a renovar.',
        ];
    }
}
