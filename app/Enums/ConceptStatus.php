<?php

namespace App\Enums;

enum ConceptStatus: int
{
    case Active = 1;
    case DeActive = 2;

    public function label(): string
    {
        return match ($this) {
            static::Active => 'فعال',
            static::DeActive => 'غیر‌فعال',
        };
    }
}
