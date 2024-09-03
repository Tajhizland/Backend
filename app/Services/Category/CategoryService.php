<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Filter\FilterServiceInterface;
 use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductRepositoryInterface  $productRepository,
        private FilterServiceInterface       $filterService,
        private S3ServiceInterface          $s3Service
    )
    {
    }

    public function listing($url, $filters)
    {
        $category = $this->categoryRepository->findByUrl($url);
        if (!$category) {
            throw new NotFoundHttpException();
        }
        $productsQuery = $this->productRepository->activeProductQuery();
        $productsQuery = $this->filterService->apply($productsQuery, $filters);
        $products = $this->productRepository->paginated($productsQuery);

        return ["products" => $products, "category" => $category];
    }

    public function list()
    {
        return $this->categoryRepository->list();
    }

    public function dataTable()
    {
        return $this->categoryRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->categoryRepository->findOrFail($id);
    }

    public function storeCategory($name, $status, $url, $image, $description, $parentId)
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "/category/");
        }
        return $this->categoryRepository->createCategory($name, $status, $url, $imagePath, $description, $parentId);
    }

    public function updateCategory($id, $name, $status, $url, $image, $description, $parentId)
    {
        $category = $this->categoryRepository->findOrFail($id);
        $imagePath = $category->image;
        if ($image) {
            $this->s3Service->remove($category->image);
            $imagePath = $this->s3Service->upload($image, "/category/");
        }
        return $this->categoryRepository->updateCategory($category, $name, $status, $url, $imagePath, $description, $parentId);
    }
}
