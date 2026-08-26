<?php

namespace App\DTOs\Cart;

class CartItemDto
{
    public function __construct(
        public int $userId,
        public int $productColorId,
        public mixed $guaranty_id = null,
    )
    {
    }
}
