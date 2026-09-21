<?php

namespace App\Services\SnappPay;

final class SnappPayPrice
{
    public static function fee(int|float $unitPrice, int|float $percent): int
    {
        // Round per unit so cartItems.amount * count matches the charged total.
        return (int) round($unitPrice * $percent / 100);
    }
}
