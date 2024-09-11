<?php

namespace App\Repositories\ProductOption;

use App\Models\ProductOption;
use App\Repositories\Base\BaseRepositoryInterface;

interface ProductOptionRepositoryInterface extends  BaseRepositoryInterface
{
    public function findProductOption($productId , $optionItemId);
    public function store($productId , $optionItemId , $value);
    public function updateValue(ProductOption $productFilter , $value);
}
