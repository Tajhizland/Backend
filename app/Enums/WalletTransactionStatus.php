<?php

namespace App\Enums;

enum WalletTransactionStatus: int
{
    case Unpaid = 0;

    case Paid = 1;


    public function label(): string
    {
        return match ($this) {
            static::Unpaid => 'پرداخت نشده',

            static::Paid => 'پرداخت شده',

        };
    }
}
