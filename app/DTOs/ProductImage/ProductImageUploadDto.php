<?php

namespace App\DTOs\ProductImage;

class ProductImageUploadDto
{
    public function __construct(
        public int $product_id,
        public mixed $image,
    )
    {
    }
}
