<?php

namespace App\Enums;

enum SolicitudEstado: string
{
    case INICIADA = 'INICIADA';
    case DATOS_CONFIRMADOS = 'DATOS_CONFIRMADOS';
    case PAGADA = 'PAGADA';
    case CITA_AGENDADA = 'CITA_AGENDADA';
    case COMPLETADA = 'COMPLETADA';
    case CANCELADA = 'CANCELADA';

    public function label(): string
    {
        return match ($this) {
            self::INICIADA => 'Iniciada',
            self::DATOS_CONFIRMADOS => 'Datos Confirmados',
            self::PAGADA => 'Aranceles Pagados',
            self::CITA_AGENDADA => 'Cita Programada en SERTRACEN',
            self::COMPLETADA => 'Trámite Completado',
            self::CANCELADA => 'Cancelada',
        };
    }
}
