<?php

namespace App\Repositories\Comment;

use App\Repositories\Base\BaseRepositoryInterface;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function createComment($productId,$text,$rating);
}
