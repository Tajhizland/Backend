<?php

namespace App\Services\Sort\Returned;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortReturnedByProductName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('order_items', 'returneds.order_item_id', '=', 'order_items.id')
            ->leftJoin('products', 'order_item_id.product_id', '=', 'products.id')
            ->orderBy('products.name', $descending ? 'desc' : 'asc');
    }
}
