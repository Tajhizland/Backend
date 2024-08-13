<?php

namespace App\Repositories\Comment;

use App\Enums\CommentStatus;
use App\Models\Comment;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Comment\SortCommentByProductName;
use App\Services\Sort\Comment\SortCommentByUserMobile;
use App\Services\Sort\Comment\SortCommentByUserName;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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
        return QueryBuilder::for(Comment::class)
            ->allowedFilters(['user_id', 'product_id', 'rating', 'status', 'created_at', "text",
                AllowedFilter::callback('user', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }), AllowedFilter::callback('mobile', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('username', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('product', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                })
            ])
            ->allowedSorts(['user_id', 'product_id', 'rating', 'status', 'created_at', "text"
                , AllowedSort::custom("product", new SortCommentByProductName())
                , AllowedSort::custom("mobile", new SortCommentByUserMobile())
                , AllowedSort::custom("user", new SortCommentByUserName())])
            ->paginate($this->pageSize);
    }

    public function accept(Comment $comment)
    {
        $comment->update([
            "status" => CommentStatus::Accepted->value
        ]);
    }

    public function reject(Comment $comment)
    {
        $comment->update([
            "status" => CommentStatus::Rejected->value
        ]);
    }
}
