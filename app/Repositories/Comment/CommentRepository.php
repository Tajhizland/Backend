<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Base\BaseRepository;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function createComment($productId, $text, $rating)
    {
        return $this->model::create([
            "product_id" => $productId,
            "text" => $text,
            "rating" => $rating
        ]);

    }
}
