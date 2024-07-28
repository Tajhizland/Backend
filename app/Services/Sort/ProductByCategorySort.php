<?php

namespace App\Services\Sort;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class ProductByCategorySort  implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query
            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->leftJoin('categories', 'product_categories.category_id', '=', 'categories.id')
            ->orderBy('categories.name', $descending ? 'desc' : 'asc');
    }
}
