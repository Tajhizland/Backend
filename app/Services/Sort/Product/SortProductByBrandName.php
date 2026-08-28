<?php

namespace App\Services\Sort\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortProductByBrandName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property): void
    {
        $query
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->orderBy('brands.name', $descending ? 'desc' : 'asc');
    }
}
