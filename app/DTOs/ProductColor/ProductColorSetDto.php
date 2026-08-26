<?php

namespace App\DTOs\ProductColor;

class ProductColorSetDto
{
    public function __construct(
        public int $product_id,
        public array $color = [],
    )
    {
    }
}
