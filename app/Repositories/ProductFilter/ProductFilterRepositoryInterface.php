<?php

namespace App\Repositories\ProductFilter;

use App\Models\ProductFilter;
use App\Repositories\Base\BaseRepositoryInterface;

interface ProductFilterRepositoryInterface extends  BaseRepositoryInterface
{
    public function findProductFilter($productId , $filterId);
    public function store($productId , $filterId , $filterItemId);
    public function updateFilterItem(ProductFilter $productFilter , $filterItemId);
}
