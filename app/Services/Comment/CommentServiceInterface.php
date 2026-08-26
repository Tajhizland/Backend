<?php

namespace App\Services\Comment;

interface CommentServiceInterface
{
    public function createComment($productId, $text, $rating , $userId);
    public function dataTable(): mixed;
    public function find(int $id): mixed;
    public function accept($id);
    public function reject($id);
}
