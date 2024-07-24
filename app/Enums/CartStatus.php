<?php

namespace App\Enums;

enum CartStatus: int
{
    case Active = 1;
    case Completed = 2;

    public function label(): string
    {
        return match ($this) {
            static::Active => 'فعال',
            static::Completed => 'تکمیل خرید',
        };
    }
}
