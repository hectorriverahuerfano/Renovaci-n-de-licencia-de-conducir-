<?php

namespace App\Enums;

enum PagoEstado: string
{
    case PENDIENTE = 'PENDIENTE';
    case APROBADO = 'APROBADO';
    case RECHAZADO = 'RECHAZADO';

    public function label(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente de Pago',
            self::APROBADO => 'Aprobado y Acreditado',
            self::RECHAZADO => 'Rechazado',
        };
    }
}
