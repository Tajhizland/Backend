<?php

namespace App\Services\Product;

use App\Exceptions\BreakException;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repositories\ProductVideo\ProductVideoRepositoryInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\ProductCategory\ProductCategoryServiceInterface;
use App\Services\ProductGuaranty\ProductGuarantyServiceInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface         $productRepository,
        private ProductCategoryServiceInterface    $productCategoryService,
        private ProductCategoryRepositoryInterface $productCategoryRepository,
        private ProductGuarantyServiceInterface    $productGuarantyService,
        private FilterServiceInterface             $filterService,
        private ProductVideoRepositoryInterface    $productVideoRepository,
        private S3ServiceInterface                 $s3Service,
    )
    {
    }

    public function getDiscountedProducts($filter): mixed
    {
        $productsQuery = $this->productRepository->getDiscountedProducts();
        $productsQuery = $this->filterService->apply($productsQuery, $filter);
        return $this->productRepository->paginated($productsQuery);
    }

    public function findProductByUrl(string $url): mixed
    {
        $product = $this->productRepository->findByUrl($url);
        if (!$product) {
            throw new ModelNotFoundException();
        }
        $this->productRepository->incrementViewCount($product);
        return $product;
    }

    public function dataTable(): mixed
    {
        return $this->productRepository->dataTable();
    }

    public function searchProduct($query): mixed
    {
        return $this->productRepository->search($query);
    }

    public function findById($id): mixed
    {
        return $this->productRepository->findById($id);
    }

    public function storeProduct($name, $url, $description, $study, $status, $categoryId, $brandId, $metaTitle, $metaDescription, $guaranty_id, $guaranty_time, $review, $type): mixed
    {
        $product = $this->productRepository->create([
            "name" => $name,
            "type" => $type,
            "url" => $url,
            "description" => $description,
            "study" => $study,
            "status" => $status,
            "view" => 0,
            "review" => $review,
            "brand_id" => $brandId,
            "guaranty_time" => $guaranty_time,
            "meta_title" => $metaTitle,
            "meta_description" => $metaDescription,
        ]);
        $categoryIds = json_decode($categoryId);
        $this->productCategoryService->syncProductCategory($product->id, $categoryIds);
        $guarantyIds = json_decode($guaranty_id);
        $this->productGuarantyService->syncProductGuaranty($product->id, $guarantyIds);
        return $product;
    }

    public function updateProduct($id, $name, $url, $description, $study, $status, $categoryId, $brandId, $metaTitle, $metaDescription, $guaranty_id, $guaranty_time, $review, $type): mixed
    {
        $product = $this->productRepository->findOrFail($id);
        $this->productRepository->update($product,
            [
                "name" => $name,
                "type" => $type,
                "url" => $url,
                "description" => $description,
                "study" => $study,
                "status" => $status,
                "guaranty_time" => $guaranty_time,
                "brand_id" => $brandId,
                "meta_title" => $metaTitle,
                "review" => $review,
                "meta_description" => $metaDescription,
            ]);
        $categoryIds = json_decode($categoryId);
        $this->productCategoryService->syncProductCategory($product->id, $categoryIds);
        $guarantyIds = json_decode($guaranty_id);
        $this->productGuarantyService->syncProductGuaranty($product->id, $guarantyIds);

        return true;
    }

    public function searchProductWithCategory($query, $categoryId): mixed
    {
        return $this->productRepository->searchProductWithCategory($query, $categoryId);
    }

    public function getRelatedProducts($id): mixed
    {
        $productCategory = $this->productCategoryRepository->findByProductId($id);
        if (!$productCategory)
            throw  new BreakException(\Lang::get("exceptions.product_not_find"));
        return $this->productRepository->getByCategoryId($productCategory->category_id, $id);
    }

    public function setVideo($productId, $vlogId, $type): mixed
    {
        $product = $this->productRepository->findOrFail($productId);
        switch ($type) {
            case "intro":
                return $this->productRepository->update($product, ["intro_video" => $vlogId]);
            case "unboxing":
                return $this->productRepository->update($product, ["unboxing_video" => $vlogId]);
            case "usage":
                return $this->productRepository->update($product, ["usage_video" => $vlogId]);
        }
        throw new BreakException(\Lang::get("exceptions.type_not_find"));
    }

    public function special(): mixed
    {
        return $this->productRepository->getSpecial();
    }

    public function getSitemapData()
    {
        return $this->productRepository->getSitemapData();
    }

    public function customPaginate($perPage)
    {
        return $this->productRepository->customPaginate($perPage);
    }

    public function torobProduct()
    {
        return $this->productRepository->getTorobProducts();
    }

    public function setVideo2($productId, $vlogId, $title)
    {
        return $this->productVideoRepository->create(
            [
                "title" => $title,
                "product_id" => $productId,
                "vlog_id" => $vlogId,
            ]);
    }

    public function getVideo($productId)
    {
        return $this->productVideoRepository->getByProductId($productId);
    }

    public function deleteVideo($id)
    {
        $productVideo = $this->productVideoRepository->findOrFail($id);
        return $this->productVideoRepository->delete($productVideo);
    }

    public function hasLimitDataTable(): mixed
    {
        return $this->productRepository->hasLimitDataTable();
    }

    public function hasDiscountDataTable(): mixed
    {
        return $this->productRepository->hasDiscountDataTable();
    }

    public function searchList($categoryId, $brandId): mixed
    {
        return $this->productRepository->searchList($categoryId, $brandId);
    }

    public function groupChangePrice($ids, $action, $percent)
    {
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $colors = $product->colors;
            if ($colors) {
                foreach ($colors as $color) {
                    $price = $color->price;
                    $currentPrice = $price->price;
                    $newPrice = $currentPrice;
                    if ($action == "inc")
                        $newPrice = $currentPrice + ($currentPrice * $percent / 100);
                    if ($action == "dec")
                        $newPrice = $currentPrice - ($currentPrice * $percent / 100);

                    $product->price = $newPrice;
                    $product->save();
                }
            }
        }
        return true;
    }
}
