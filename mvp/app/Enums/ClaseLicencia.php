<?php

namespace App\Enums;

enum ClaseLicencia: string
{
    case PARTICULAR = 'PARTICULAR';
    case LIVIANA = 'LIVIANA';
    case PESADA = 'PESADA';
    case PESADA_T = 'PESADA_T';
    case MOTO = 'MOTO';
    case JUVENIL_PARTICULAR = 'JUVENIL_PARTICULAR';
    case JUVENIL_MOTO = 'JUVENIL_MOTO';

    public function label(): string
    {
        return match ($this) {
            self::PARTICULAR => 'Particular (Automóviles y camionetas hasta 3.5T)',
            self::LIVIANA => 'Liviana (Microbuses, camionetas y camiones hasta 5T)',
            self::PESADA => 'Pesada (Camiones y buses articulados)',
            self::PESADA_T => 'Pesada T (Cabezales y transporte de carga pesada)',
            self::MOTO => 'Motocicleta (Toda cilindrada)',
            self::JUVENIL_PARTICULAR => 'Juvenil Particular (Menores de 15 a 17 años)',
            self::JUVENIL_MOTO => 'Juvenil Moto (Menores de 15 a 17 años)',
        };
    }

    public function shortName(): string
    {
        return match ($this) {
            self::PARTICULAR => 'Particular',
            self::LIVIANA => 'Liviana',
            self::PESADA => 'Pesada',
            self::PESADA_T => 'Pesada T',
            self::MOTO => 'Motocicleta',
            self::JUVENIL_PARTICULAR => 'Juvenil Particular',
            self::JUVENIL_MOTO => 'Juvenil Moto',
        };
    }

    public function vigenciaAnios(): int
    {
        return match ($this) {
            self::JUVENIL_PARTICULAR, self::JUVENIL_MOTO => 1,
            default => 5,
        };
    }
}
