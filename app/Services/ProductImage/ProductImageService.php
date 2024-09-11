<?php

namespace App\Services\ProductImage;

use App\Repositories\ProductImage\ProductImageRepositoryInterface;

class ProductImageService implements ProductImageServiceInterface
{
    public function __construct(
        private ProductImageRepositoryInterface $productImageRepository
    )
    {
    }

    public function getByProductId($productId)
    {
        return $this->productImageRepository->getByProductId($productId);
    }
}
