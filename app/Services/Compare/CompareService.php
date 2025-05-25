<?php

namespace App\Services\Compare;

use App\Repositories\Product\ProductRepositoryInterface;

class CompareService implements CompareServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function findProductCompare($productId)
    {
        return $this->productRepository->findProductWithOption($productId);
    }

    public function searchProductCompare($query, $categoryIds)
    {
        return $this->productRepository->searchWithOption($query, $categoryIds);
    }
}
