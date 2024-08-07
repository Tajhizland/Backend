<?php

namespace App\Enums;

enum CommentStatus: int
{
    case Pending = 0;
    case Accepted = 1;
    case Rejected = 2;

    public function label(): string
    {
        return match ($this) {
            static::Pending => 'در انتظار تایید',
            static::Accepted => 'تایید شده',
            static::Rejected => 'رد شده',
         };
    }
}
