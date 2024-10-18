<?php

namespace App\Services\Comment;

interface CommentServiceInterface
{
    public function createComment($productId, $text, $rating , $userId);
    public function dataTable();
    public function findById($id);
    public function accept($id);
    public function reject($id);
}
