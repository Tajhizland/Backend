<?php

namespace App\Services\Sort\Transaction;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortTransactionByUserMobile implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->orderBy('users.username', $descending ? 'desc' : 'asc');
    }
}
