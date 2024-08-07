<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Base\BaseRepositoryInterface;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function createComment($productId,$text,$rating);
    public function dataTable();
    public function accept(Comment $comment);
    public function reject(Comment $comment);
}
