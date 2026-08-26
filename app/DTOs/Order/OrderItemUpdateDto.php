<?php

namespace App\DTOs\Order;

class OrderItemUpdateDto
{
    public function __construct(
        public int $orderItemId,
        public int $count,
    )
    {
    }
}
