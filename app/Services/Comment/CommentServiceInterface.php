<?php

namespace App\Services\Comment;

interface CommentServiceInterface
{
    public function createComment($productId, $text, $rating);
}
