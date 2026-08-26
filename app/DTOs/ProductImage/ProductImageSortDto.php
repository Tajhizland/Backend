<?php

namespace App\DTOs\ProductImage;

class ProductImageSortDto
{
    public function __construct(
        public array $image = [],
    )
    {
    }
}
