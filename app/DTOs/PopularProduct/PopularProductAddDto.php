<?php

namespace App\DTOs\PopularProduct;

class PopularProductAddDto
{
    public function __construct(
        public int $product_id,
    )
    {
    }
}
