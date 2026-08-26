<?php

namespace App\DTOs\Delivery;

class DeliverySelectDto
{
    public function __construct(
        public int $userId,
        public int $id,
    )
    {
    }
}
