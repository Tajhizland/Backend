<?php

namespace App\Repositories\Comment;

use App\Enums\CommentStatus;
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
            "status" => CommentStatus::Pending->value,
            "rating" => $rating
        ]);

    }
    public function dataTable()
    {
        // TODO: Implement dataTable() method.
    }

    public function accept(Comment $comment)
    {
        $comment->update([
            "status"=>CommentStatus::Accepted->value
        ]);
    }
    public function reject(Comment $comment)
    {
        $comment->update([
            "status"=>CommentStatus::Rejected->value
        ]);
    }
}
