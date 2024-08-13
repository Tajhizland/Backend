<?php

namespace App\Services\Sort\Comment;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortCommentByProductName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('products', 'comments.product_id', '=', 'products.id')
            ->orderBy('products.name', $descending ? 'desc' : 'asc');
    }
}
