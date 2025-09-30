<?php

namespace App\Enums;

enum SmsLogStatus: int
{
    case Pending = 1;
    case Completed = 2;

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'در حال ارسال',
            static::Completed => 'تکمیل شده',
        };
    }
}
