<?php

namespace App\Services\PopularProduct;

use App\Repositories\PopularProduct\PopularProductRepositoryInterface;

class PopularProductService implements  PopularProductServiceInterface
{
    public function __construct(private PopularProductRepositoryInterface $popularProductRepository)
    {
    }

    public function dataTable()
    {
       return $this->popularProductRepository->dataTable();
    }

    public function add($productId)
    {
        return $this->popularProductRepository->add($productId);
    }
    public function get()
    {
        return  $this->popularProductRepository->getWithProduct();
    }

    public function delete($id)
    {
        $item= $this->popularProductRepository->findOrFail($id);
        return $this->popularProductRepository->delete($item);
    }
}
