<?php

namespace App\Enums;

enum ReturnedStatus: int
{
    case Pending = 0;
    case Accept = 1;
    case Reject = 2;

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'در حال بررسی',
            static::Accept => 'تایید شده',
            static::Reject => 'رد شده',
        };
    }
}
