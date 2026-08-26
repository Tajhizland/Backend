<?php

namespace App\DTOs\Order;

class TapinRegisterDto
{
    public function __construct(
        public int $orderId,
        public int $status,
    )
    {
    }
}
