<?php

namespace App\Services;

use App\Enums\CitaEstado;
use App\Enums\SolicitudEstado;
use App\Models\Cita;
use App\Models\Notificacion;
use App\Models\Solicitud;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AppointmentService
{
    /**
     * Obtiene el listado de sucursales disponibles ordenadas con la recomendada al inicio.
     */
    public function getBranches(): Collection
    {
        return Sucursal::where('activo', true)
            ->orderByDesc('es_recomendada')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Genera los bloques horarios del día con conteo de cupos disponibles.
     */
    public function getAvailableSlots(Sucursal $sucursal, string $date): array
    {
        $baseSlots = [
            '08:30 AM',
            '09:15 AM',
            '10:00 AM',
            '10:45 AM',
            '11:30 AM',
            '01:15 PM',
            '02:00 PM',
            '02:45 PM',
            '03:30 PM',
        ];

        $slots = [];
        $capacidad = $sucursal->capacidad_por_hora;

        foreach ($baseSlots as $index => $time) {
            // Contar citas ya agendadas en ese slot
            $ocupadas = Cita::where('sucursal_id', $sucursal->id)
                ->where('fecha_cita', $date)
                ->where('hora_cita', $time)
                ->where('estado', 'PROGRAMADA')
                ->count();

            $disponibles = max(0, $capacidad - $ocupadas);

            $slots[] = [
                'time' => $time,
                'available' => $disponibles > 0,
                'remaining' => $disponibles,
                'is_fast_flow' => ($index % 2 === 0), // Indicador de Fila Express ágil
            ];
        }

        return $slots;
    }

    /**
     * Agenda una cita oficial para el examen visual y foto biométrica en SERTRACEN.
     */
    public function bookAppointment(
        Solicitud $solicitud,
        int $sucursalId,
        string $fecha,
        string $hora,
        string $tipoAtencion = 'ESTANDAR_EXPRESS',
        bool $lentes = false
    ): Cita {
        $cita = Cita::create([
            'solicitud_id' => $solicitud->id,
            'sucursal_id' => $sucursalId,
            'fecha_cita' => $fecha,
            'hora_cita' => $hora,
            'tipo_atencion' => $tipoAtencion,
            'estado' => CitaEstado::PROGRAMADA,
            'checklist_dui_confirmado' => true,
            'checklist_licencia_confirmada' => true,
            'checklist_lentes_confirmado' => $lentes,
        ]);

        $solicitud->update([
            'estado' => SolicitudEstado::CITA_AGENDADA,
            'paso_actual' => 5,
        ]);

        // Registrar notificación SMS y Email
        if ($solicitud->correo_contacto) {
            Notificacion::create([
                'ciudadano_id' => $solicitud->ciudadano_id,
                'solicitud_id' => $solicitud->id,
                'canal' => 'EMAIL',
                'tipo' => 'CONFIRMACION_CITA',
                'destinatario' => $solicitud->correo_contacto,
                'mensaje' => "Su cita para renovación de licencia ha sido programada con éxito para el {$fecha} a las {$hora} en SERTRACEN.",
                'estado' => 'ENVIADO',
            ]);
        }

        return $cita;
    }

    /**
     * Reprograma una cita existente sin costo adicional.
     */
    public function reschedule(Cita $cita, int $nuevaSucursalId, string $nuevaFecha, string $nuevaHora): Cita
    {
        $cita->update([
            'sucursal_id' => $nuevaSucursalId,
            'fecha_cita' => $nuevaFecha,
            'hora_cita' => $nuevaHora,
            'estado' => CitaEstado::REPROGRAMADA,
        ]);

        return $cita;
    }
}
