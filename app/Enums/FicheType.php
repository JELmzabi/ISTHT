<?php

namespace App\Enums\Enums;

enum FicheType : string
{
    case COLLECTIVITE = 'collectivite';
    case PEDAGOGIQUE = 'pedagogique';

    public function label(): string
    {
        return match ($this) {
            self::COLLECTIVITE => 'Collectivité',
            self::PEDAGOGIQUE => 'Pédagogique',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
