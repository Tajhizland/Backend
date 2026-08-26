<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\User;

interface OrderPaymentFinalizerInterface
{
    public function markPaid(Order $order, int|float $walletDeduction = 0, $trackId = null, ?User $user = null): void;

    public function applyPayment(Order $order, int|float $walletDeduction = 0, $trackId = null, ?User $user = null): void;
}
