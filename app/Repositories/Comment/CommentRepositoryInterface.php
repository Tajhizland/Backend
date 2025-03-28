<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Base\BaseRepositoryInterface;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function createComment($productId,$text,$rating ,$userId);
    public function dataTable();
    public function findWithProduct($id);
    public function accept(Comment $comment);
    public function reject(Comment $comment);
    public function todayCommentCount();
}
