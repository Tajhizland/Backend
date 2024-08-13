<?php

namespace App\Services\Sort\Returned;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortReturnedByUserMobile implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('users', 'returneds.user_id', '=', 'users.id')
            ->orderBy('users.username', $descending ? 'desc' : 'asc');
    }
}
