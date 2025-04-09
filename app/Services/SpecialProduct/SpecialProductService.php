<?php

namespace App\Services\SpecialProduct;

use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;

class SpecialProductService implements SpecialProductServiceInterface
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
        $item = $this->specialProductRepository->findOrFail($id);
        return $this->specialProductRepository->delete($item);
    }

    public function showHomepage($id, $value)
    {
        $item = $this->specialProductRepository->findOrFail($id);
        return $this->specialProductRepository->update($item, ["homepage" => $value]);
    }

    public function sort($product)
    {
        foreach ($product as $item) {
            $this->specialProductRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
