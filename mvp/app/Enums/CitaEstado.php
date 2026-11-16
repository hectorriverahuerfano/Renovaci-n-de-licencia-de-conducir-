<?php

namespace App\Enums;

enum CitaEstado: string
{
    case PROGRAMADA = 'PROGRAMADA';
    case ATENDIDA = 'ATENDIDA';
    case REPROGRAMADA = 'REPROGRAMADA';
    case CANCELADA = 'CANCELADA';

    public function label(): string
    {
        return match ($this) {
            self::PROGRAMADA => 'Cita Programada',
            self::ATENDIDA => 'Atendido en Sucursal',
            self::REPROGRAMADA => 'Cita Reprogramada',
            self::CANCELADA => 'Cancelada',
        };
    }
}
