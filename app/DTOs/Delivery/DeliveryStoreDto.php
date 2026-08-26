<?php

namespace App\DTOs\Delivery;

class DeliveryStoreDto
{
    public function __construct(
        public string  $name,
        public int     $status,
        public int     $price,
        public ?string $description = null,
        public mixed   $logo = null,
    )
    {
    }
}
