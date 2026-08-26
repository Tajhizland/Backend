<?php

namespace App\DTOs\Discount;

class DiscountUpdateItemDto
{
    public function __construct(
        public array $discount,
    )
    {
    }
}
