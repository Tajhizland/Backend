<?php

namespace App\DTOs\Checkout;

class CheckoutSetGatewayDto
{
    public function __construct(
        public int $userId,
        public int $gateway_id,
    )
    {
    }
}
