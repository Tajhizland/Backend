<?php

namespace App\Services\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class CommentService implements CommentServiceInterface
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    )
    {
    }

    public function createComment($productId, $text, $rating)
    {
        return $this->commentRepository->createComment($productId, $text, $rating);
    }
}
