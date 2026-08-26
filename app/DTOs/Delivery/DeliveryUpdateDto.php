<?php

namespace App\DTOs\Delivery;

class DeliveryUpdateDto
{
    public function __construct(
        public int     $deliveryId,
        public string  $name,
        public int     $status,
        public int     $price,
        public ?string $description = null,
        public mixed   $logo = null,
    )
    {
    }
}
