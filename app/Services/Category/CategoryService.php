<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\CategoryTree\CategoryTreeServiceInterface;
use App\Services\Breadcrumb\BreadcrumbServiceInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface  $categoryRepository,
        private ProductRepositoryInterface   $productRepository,
        private FilterServiceInterface       $filterService,
        private BreadcrumbServiceInterface   $breadcrumbService,
        private CategoryTreeServiceInterface $categoryTreeService,
        private S3ServiceInterface           $s3Service
    )
    {
    }

    public function searchCategory($query)
    {
        return $this->categoryRepository->search($query);
    }

    public function listing($url, $filters)
    {
        $category = $this->categoryRepository->findByUrl($url);
        if (!$category) {
            throw new NotFoundHttpException();
        }
        $categoryIds = $this->categoryTreeService->getCategoryAndChildrenIds($category);

        $productsQuery = $this->productRepository->activeProductQuery($categoryIds);
        $productsQuery = $this->filterService->apply($productsQuery, $filters);
        $products = $this->productRepository->paginated($productsQuery);
        $breadcrumb = $this->breadcrumbService->generate($category);

        return ["products" => $products, "category" => $category, "breadcrumb" => $breadcrumb];
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

    public function storeCategory($name, $status, $url, $image, $description, $parentId,$type)
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "category");
        }
        return $this->categoryRepository->create(
            [
                "name" => $name,
                "status" => $status,
                "url" => $url,
                "image" => $imagePath,
                "description" => $description,
                "type" => $type,
                "parent_id" => $parentId
            ]
        );
    }

    public function updateCategory($id, $name, $status, $url, $image, $description, $parentId,$type)
    {
        $category = $this->categoryRepository->findOrFail($id);
        $imagePath = $category->image;
        if ($image) {
            $this->s3Service->remove("category/" . $category->image);
            $imagePath = $this->s3Service->upload($image, "category");
        }
        return $this->categoryRepository->update($category,
            [
                "name" => $name,
                "status" => $status,
                "url" => $url,
                "image" => $imagePath,
                "description" => $description,
                "type" => $type,
                "parent_id" => $parentId
            ]);
    }

    public function productList($id)
    {
        return $this->productRepository->getAllByCategoryId($id);
    }

    public function productSort($array)
    {
        foreach ($array as $item) {
            $this->productRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function deleteImage($categoryId)
    {
        $category = $this->categoryRepository->findOrFail($categoryId);
        $this->s3Service->remove("category/" . $category->image);
        return $this->categoryRepository->update($category, ["image" => ""]);
    }

    public function getSitemapData()
    {
        return $this->categoryRepository->getSitemapData();
    }

    public function getDiscountedCategory()
    {
        return $this->categoryRepository->getDiscountedCategory();
    }
}
