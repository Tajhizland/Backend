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

    public function dataTable()
    {
        return $this->commentRepository->dataTable();
    }

    public function accept($id)
    {
        $comment=$this->commentRepository->findOrFail($id);
       return $this->commentRepository->accept($comment);
    }

    public function reject($id)
    {
        $comment=$this->commentRepository->findOrFail($id);
      return  $this->commentRepository->reject($comment);
    }
    public function findById($id)
    {
        return $this->commentRepository->findWithProduct($id);
    }
}
