<?php

namespace App\Services\Comment;

use App\DTOs\Comment\CommentStoreDto;

interface CommentServiceInterface
{
    public function createComment(CommentStoreDto $dto): mixed;
    public function dataTable(): mixed;
    public function find(int $id): mixed;
    public function accept($id);
    public function reject($id);
}
