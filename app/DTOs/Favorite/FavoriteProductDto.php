<?php

namespace App\DTOs\Favorite;

class FavoriteProductDto
{
    public function __construct(
        public int $userId,
        public int $productId,
    )
    {
    }
}
