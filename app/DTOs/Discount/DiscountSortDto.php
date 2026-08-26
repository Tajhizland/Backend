<?php

namespace App\DTOs\Discount;

class DiscountSortDto
{
    public function __construct(
        public array $discounts,
    )
    {
    }
}
