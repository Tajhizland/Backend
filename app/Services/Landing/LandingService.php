<?php

namespace App\Services\Landing;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Landing\LandingRepositoryInterface;
use App\Repositories\LandingCategory\LandingCategoryRepositoryInterface;
use App\Repositories\LandingProduct\LandingProductRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class LandingService implements LandingServiceInterface
{
    public function __construct
    (
        private LandingRepositoryInterface         $landingRepository,
        private LandingCategoryRepositoryInterface $landingCategoryRepository,
        private LandingProductRepositoryInterface  $landingProductRepository,
    )
    {
    }

    public function store($title, $description, $status, $url)
    {
        return $this->landingRepository->create([
            "title" => $title,
            "description" => $description,
            "status" => $status,
            "url" => $url,
        ]);
    }

    public function update($id, $title, $description, $status, $url)
    {
        $landing = $this->landingRepository->findOrFail($id);
        return $this->landingRepository->update($landing, [
            "title" => $title,
            "description" => $description,
            "status" => $status,
            "url" => $url,
        ]);
    }

    public function findById($id)
    {
        return $this->landingRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->landingRepository->dataTable();
    }

    public function setProduct($landingId, $productId)
    {
        return $this->landingProductRepository->create(["landing_id" => $landingId, "product_id" => $productId]);
    }

    public function setCategory($landingId, $categoryId)
    {
        return $this->landingCategoryRepository->create(["landing_id" => $landingId, "category_id" => $categoryId]);
    }

    public function deleteProduct($id)
    {
        $landingProduct = $this->landingProductRepository->findOrFail($id);
        return $this->landingProductRepository->delete($landingProduct);
    }

    public function deleteCategory($id)
    {
        $landingCategory = $this->landingCategoryRepository->findOrFail($id);
        return $this->landingCategoryRepository->delete($landingCategory);
    }

    public function findByUrl($url)
    {
        return $this->landingRepository->findByUrl($url);
    }

    public function getProductByLanding($landingId)
    {

        return $this->landingProductRepository->getWithProduct($landingId);    }

    public function getCategoryByLanding($landingId)
    {
        return $this->landingCategoryRepository->getWithCategory($landingId);
    }
}
