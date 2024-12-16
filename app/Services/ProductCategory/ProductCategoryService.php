<?php

namespace App\Services\ProductCategory;

use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;

class ProductCategoryService implements  ProductCategoryServiceInterface
{
    public function __construct
    (
        private  ProductCategoryRepositoryInterface $productCategoryRepository
    )
    {
    }

    public function syncProductCategory($productId, $categoryIds)
    {
        $this->productCategoryRepository->deleteByProductId($productId);
        foreach ($categoryIds as $item)
        {
            $this->productCategoryRepository->create([
                "product_id"=>$productId,
                "category_id"=>$item
            ]);
        }
    }
}
