<?php

namespace App\Services\Compare;

use App\Enums\MarketingEventType;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Marketing\MarketingTrackerServiceInterface;

readonly class CompareService implements CompareServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface $productRepository,
        private MarketingTrackerServiceInterface $marketingTrackerService
    )
    {
    }

    public function findProductCompare($productId)
    {
        $product = $this->productRepository->findProductWithOption($productId);
        // هر بار که محصولی داخل جدول مقایسه باز می‌شود یک رویداد ثبت می‌شود.
        $this->marketingTrackerService->track(MarketingEventType::Compare, $product?->id ?? (int)$productId);
        return $product;
    }

    public function searchProductCompare($query, $categoryIds)
    {
        return $this->productRepository->searchWithOption($query, $categoryIds);
    }

    public function getProducts($categoryIds)
    {
        return $this->productRepository->getWithOption($categoryIds);

    }
}
