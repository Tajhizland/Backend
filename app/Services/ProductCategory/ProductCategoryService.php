<?php

namespace App\Services\ProductCategory;

use App\Repositories\Option\OptionRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repositories\ProductOption\ProductOptionRepositoryInterface;

class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function __construct
    (
        private ProductCategoryRepositoryInterface $productCategoryRepository,
        private OptionRepositoryInterface          $optionRepository,
        private ProductOptionRepositoryInterface   $productOptionRepository
    )
    {
    }

    public function syncProductCategory($productId, $categoryIds)
    {
        $oldCategory = $this->productCategoryRepository->findByProductId($productId);
        if ($oldCategory && !in_array($oldCategory->category_id, $categoryIds)) {
            $options = $this->optionRepository->getCategoryOptions($oldCategory->category_id);
            foreach ($options as $item) {
                foreach ($item->optionItems as $optionItem) {
                    $productOption = $this->productOptionRepository->findProductOption($productId, $optionItem->id);
                    $this->productOptionRepository->delete($productOption);
                }
            }
        }
        $this->productCategoryRepository->deleteByProductId($productId);

        foreach ($categoryIds as $item) {
            $this->productCategoryRepository->create([
                "product_id" => $productId,
                "category_id" => $item
            ]);
        }
    }
}
