<?php

namespace App\DTOs\Comment;

class CommentStoreDto
{
    public function __construct(
        public int $userId,
        public int $productId,
        public int $rating,
        public mixed $text,
    )
    {
    }
}
