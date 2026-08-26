<?php

namespace App\DTOs\Discount;

class DiscountSetItemDto
{
    public function __construct(
        public int   $discount_id,
        public array $discount,
    )
    {
    }
}
