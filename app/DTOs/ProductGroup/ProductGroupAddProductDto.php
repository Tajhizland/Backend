<?php

namespace App\DTOs\ProductGroup;

class ProductGroupAddProductDto
{
    public function __construct(
        public int $productId,
        public int $groupId,
    )
    {
    }
}
