<?php

namespace App\DTOs\Product;

class ProductSetVideo2Dto
{
    public function __construct(
        public int $product_id,
        public string $title,
        public int $vlogId,
    )
    {
    }
}
