<?php

namespace App\Enums;

enum CommentStatus: int
{
    case Pending = 0;
    case Confirmed = 1;
    case Rejected = 2;

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'در انتظار تایید',
            static::Confirmed => 'تایید شده',
            static::Rejected => 'رد شده',
         };
    }
}
