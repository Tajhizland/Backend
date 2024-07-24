<?php

namespace App\Enums;

enum ResetPasswordStatus:int
{
    case Pending =   0;
    case InProgress =   1;
    case Completed = 2;

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'بلاتکلیف',
            static::InProgress => 'درحال پرازش',
            static::Completed => 'تکمیل شده',
        };
    }
}
