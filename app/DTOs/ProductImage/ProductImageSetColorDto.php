<?php

namespace App\DTOs\ProductImage;

class ProductImageSetColorDto
{
    public function __construct(
        public int $product_id,
        public array $image = [],
    )
    {
    }
}
