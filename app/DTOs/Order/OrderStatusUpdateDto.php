<?php

namespace App\DTOs\Order;

class OrderStatusUpdateDto
{
    public function __construct(
        public int $orderId,
        public int $status,
    )
    {
    }
}
