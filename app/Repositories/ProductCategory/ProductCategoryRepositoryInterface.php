<?php

namespace App\Repositories\ProductCategory;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductCategoryRepositoryInterface extends  BaseRepositoryInterface
{
    public function createProductCategory($productId,$categoryId);
    public function updateWithProductId($productId,$categoryId);
}
