<?php

namespace App\DTOs\Product;

class ProductSetOptionDto
{
    public function __construct(
        public int $product_id,
        public array $option = [],
    )
    {
    }
}
