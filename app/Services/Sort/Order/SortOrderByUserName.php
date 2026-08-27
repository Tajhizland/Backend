<?php

namespace App\Services\Sort\Order;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortOrderByUserName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property): void
    {
        $query
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->orderBy('users.name', $descending ? 'desc' : 'asc');
    }
}
