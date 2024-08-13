<?php

namespace App\Services\Sort\Comment;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortCommentByUserName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->orderBy('users.username', $descending ? 'desc' : 'asc');
    }
}
