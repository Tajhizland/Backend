<?php

namespace App\Services\Category;

use App\DTOs\Category\CategoryProductSortDto;
use App\DTOs\Category\CategoryStoreDto;
use App\DTOs\Category\CategoryUpdateDto;
use App\Repositories\Category\CategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Breadcrumb\BreadcrumbServiceInterface;
use App\Services\CategoryTree\CategoryTreeServiceInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\S3\S3ServiceInterface;

readonly class CategoryService implements CategoryServiceInterface
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
        $children = $this->categoryRepository->getByIdsExpect($categoryIds, $category->id);
        $productsQuery = $this->productRepository->activeProductQuery($categoryIds);
        $productsQuery = $this->filterService->apply($productsQuery, $filters);
        $products = $this->productRepository->paginated($productsQuery);
        $groups = $this->productRepository->activeGroupLimit($categoryIds);
        $breadcrumb = $this->breadcrumbService->generate($category);

        return [
            "products" => $products,
            "groups" => $groups,
            "category" => $category,
            "breadcrumb" => $breadcrumb,
            "children" => $children
        ];
    }
    public function groupListing($url)
    {
        $category = $this->categoryRepository->findByUrl($url);
        if (!$category) {
            throw new NotFoundHttpException();
        }
        $categoryIds = $this->categoryTreeService->getCategoryAndChildrenIds($category);
        $groups = $this->productRepository->activeGroupPaginate($categoryIds);
        $breadcrumb = $this->breadcrumbService->generate($category);

        return [
            "groups" => $groups,
            "category" => $category,
            "breadcrumb" => $breadcrumb,
        ];
    }

    public function list()
    {
        return $this->categoryRepository->list();
    }

    public function dataTable()
    {
        return $this->categoryRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new NotFoundHttpException();
        }
        return $category;
    }

    public function store(CategoryStoreDto $dto): mixed
    {
        $imagePath = null;
        if ($dto->image) {
            $imagePath = $this->s3Service->upload($dto->image, "category");
        }
        return $this->categoryRepository->create([
            "name" => $dto->name,
            "status" => $dto->status,
            "url" => $dto->url,
            "image" => $imagePath,
            "description" => $dto->description,
            "type" => $dto->type,
            "parent_id" => $dto->parent_id,
        ]);
    }

    public function update(CategoryUpdateDto $dto): bool
    {
        $category = $this->find($dto->categoryId);
        $imagePath = $category->image;
        if ($dto->image) {
            $this->s3Service->remove("category/" . $category->image);
            $imagePath = $this->s3Service->upload($dto->image, "category");
        }
        return $this->categoryRepository->update($category, [
            "name" => $dto->name,
            "status" => $dto->status,
            "url" => $dto->url,
            "image" => $imagePath,
            "description" => $dto->description,
            "type" => $dto->type,
            "parent_id" => $dto->parent_id,
        ]);
    }

    public function productList($id)
    {
        return $this->productRepository->getAllByCategoryId($id);
    }

    public function productSort(CategoryProductSortDto $dto): bool
    {
        foreach ($dto->product as $item) {
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
        $productIds = $this->productRepository->getDiscountedProductsId();
        return $this->categoryRepository->getCategoryByProductId($productIds);
    }
    public function getStockProductCategory()
    {
        $productIds = $this->productRepository->getStockProductIds();
        return $this->categoryRepository->getCategoryByProductId($productIds);
    }
}
