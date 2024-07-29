<?php

namespace App\Enums;

enum FilterStatus: int
{
    case Active = 1;
    case InActive = 0;

    public function label(): string
    {
        return match ($this) {
            static::Active => 'فعال',
            static::InActive => 'غیر فعال',
        };
    }
}
