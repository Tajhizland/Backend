<?php

namespace App\Services\Sort\Order;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortOrderByUserMobile implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->orderBy('users.username', $descending ? 'desc' : 'asc');
    }
}
