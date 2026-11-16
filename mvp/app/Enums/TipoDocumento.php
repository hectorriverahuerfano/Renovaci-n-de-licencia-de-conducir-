<?php

namespace App\Enums;

enum TipoDocumento: string
{
    case DUI = 'DUI';
    case RESIDENTE_TEMPORAL = 'RESIDENTE_TEMPORAL';
    case RESIDENTE_DEFINITIVO = 'RESIDENTE_DEFINITIVO';
    case DIPLOMATICO = 'DIPLOMATICO';

    public function label(): string
    {
        return match ($this) {
            self::DUI => 'Documento Único de Identidad (DUI)',
            self::RESIDENTE_TEMPORAL => 'Carnet de Residente Temporal',
            self::RESIDENTE_DEFINITIVO => 'Carnet de Residente Definitivo',
            self::DIPLOMATICO => 'Carnet de Identidad Diplomática',
        };
    }
}
