<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Unpaid = 0;
    case OnHold = 1;
    case Rejected = 2;
    case Accepted = 3;
    case Cancelled = 4;
    case Returned = 5;
    case Paid = 6;
    case Processing = 7;
    case Shipped = 8;
    case Delivered = 9;


    public function label(): string
    {
        return match ($this) {
            static::Unpaid => 'پرداخت نشده',
            static::OnHold => 'در حال بررسی',
            static::Rejected => 'رد شده توسط مدیر',
            static::Accepted => 'تایید شده توسط مدیر',
            static::Cancelled => 'کنسل شده',
            static::Returned => 'مرجوع شده',
            static::Paid => 'پرداخت شده',
            static::Processing => 'در حال پردازش',
            static::Shipped => 'ارسال شده',
            static::Delivered => 'تحویل داده شده',

        };
    }
}
