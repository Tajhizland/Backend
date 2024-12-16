<?php

namespace App\Services\ProductCategory;

interface ProductCategoryServiceInterface
{
    public function syncProductCategory($productId , $categoryIds);
}
