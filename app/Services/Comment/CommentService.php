<?php

namespace App\Services\Comment;

use App\DTOs\Comment\CommentStoreDto;

use App\Enums\CommentStatus;
use App\Repositories\Comment\CommentRepositoryInterface;

readonly class CommentService implements CommentServiceInterface
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    )
    {
    }

    public function createComment(CommentStoreDto $dto): mixed
    {
        $productId = $dto->productId;
        $text = $dto->text;
        $rating = $dto->rating;
        $userId = $dto->userId;
        return $this->commentRepository->create(
            [
                "product_id" => $productId,
                "text" => $text,
                "status" => CommentStatus::Pending->value,
                "user_id" => $userId,
                "rating" => $rating
            ]
        );
    }

    public function dataTable(): mixed
    {
        return $this->commentRepository->dataTable();
    }

    public function accept($id)
    {
        $comment = $this->commentRepository->findOrFail($id);
        return $this->commentRepository->accept($comment);
    }

    public function reject($id)
    {
        $comment = $this->commentRepository->findOrFail($id);
        return $this->commentRepository->reject($comment);
    }

    public function find(int $id): mixed
    {
        return $this->commentRepository->findWithProduct($id);
    }
}
