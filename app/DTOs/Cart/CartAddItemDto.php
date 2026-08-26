<?php

namespace App\DTOs\Cart;

class CartAddItemDto
{
    public function __construct(
        public int $userId,
        public int $productColorId,
        public int $count,
        public mixed $guaranty_id = null,
    )
    {
    }
}
