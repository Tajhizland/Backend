<?php

namespace App\Services\ProductGuaranty;

use App\Repositories\ProductGuaranty\ProductGuarantyRepositoryInterface;

class ProductGuarantyService implements ProductGuarantyServiceInterface
{
    public function __construct
    (
        private ProductGuarantyRepositoryInterface $productGuarantyRepository
    )
    {
    }

    public function syncProductGuaranty($productId, $guarantyids)
    {
        $this->productGuarantyRepository->deleteByProductId($productId);
        if ($guarantyids)
            foreach ($guarantyids as $item) {
                $this->productGuarantyRepository->create([
                    "product_id" => $productId,
                    "guaranty_id" => $item
                ]);
            }
    }
}
