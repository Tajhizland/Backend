<?php

namespace App\DTOs\Product;

class ProductSetVideoDto
{
    public function __construct(
        public string $type,
        public int $productId,
        public mixed $vlogId = null,
    )
    {
    }
}
