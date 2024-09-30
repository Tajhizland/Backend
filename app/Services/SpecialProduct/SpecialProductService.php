<?php

namespace App\Services\SpecialProduct;

use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;

class SpecialProductService implements  SpecialProductServiceInterface
{
    public function __construct(private SpecialProductRepositoryInterface $specialProductRepository)
    {
    }

    public function dataTable()
    {
        return $this->specialProductRepository->dataTable();
    }

    public function add($productId)
    {
        return $this->specialProductRepository->add($productId);
    }

    public function delete($id)
    {
        $item= $this->specialProductRepository->findOrFail($id);
        return $this->specialProductRepository->delete($item);
    }
}