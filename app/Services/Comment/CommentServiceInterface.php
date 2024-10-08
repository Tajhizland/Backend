<?php

namespace App\Services\Comment;

interface CommentServiceInterface
{
    public function createComment($productId, $text, $rating);
    public function dataTable();
    public function findById($id);
    public function accept($id);
    public function reject($id);
}
