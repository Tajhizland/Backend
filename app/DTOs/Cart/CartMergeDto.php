<?php

namespace App\DTOs\Cart;

class CartMergeDto
{
    public function __construct(
        public int $userId,
        public array $items,
    )
    {
    }
}
