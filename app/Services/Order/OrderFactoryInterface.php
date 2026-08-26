<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\Order\Data\OrderDraft;

interface OrderFactoryInterface
{
    public function createFromCart(OrderDraft $draft): Order;

    public function holdForApproval(Order $order): void;
}
