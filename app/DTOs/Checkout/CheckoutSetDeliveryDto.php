<?php

namespace App\DTOs\Checkout;

class CheckoutSetDeliveryDto
{
    public function __construct(
        public int $userId,
        public int $delivery_id,
    )
    {
    }
}
