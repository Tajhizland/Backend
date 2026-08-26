<?php

namespace App\DTOs\Product;

class ProductGroupDigipayDto
{
    public function __construct(
        public mixed $digipay,
        public array $ids = [],
    )
    {
    }
}
