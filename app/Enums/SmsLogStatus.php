<?php

namespace App\Enums;

enum SmsLogStatus: string
{
    case Pending = "pending";
    case Completed = "completed";

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'در حال ارسال',
            static::Completed => 'تکمیل شده',
        };
    }
}
