<?php

namespace App\Enums;

enum FilterStatus: int
{
    case Active = 1;
    case DeActive = 0;

    public function label(): string
    {
        return match ($this) {
            static::Active => 'فعال',
            static::DeActive => 'غیر فعال',
        };
    }
}