<?php

namespace App\Enums;

enum ProductColorStatus: int
{
    case Limit = 2;
    case Active = 1;
    case DeActive = 0;

    public function label(): string
    {
        return match ($this) {
            static::Limit => 'محدودیت',
            static::Active => 'فعال',
            static::DeActive => 'غیر فعال',
        };
    }
}
