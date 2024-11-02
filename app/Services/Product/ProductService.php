<?php

namespace App\Services\Product;

use App\Exceptions\BreakException;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface         $productRepository,
        private ProductCategoryRepositoryInterface $productCategoryRepository,
        private S3ServiceInterface                 $s3Service,
    )
    {
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

    public function storeProduct($name, $url, $description, $study, $status, $categoryId, $brandId, $metaTitle, $metaDescription, $guaranty_id): mixed
    {
        $product = $this->productRepository->create([
            "name" => $name,
            "url" => $url,
            "description" => $description,
            "study" => $study,
            "status" => intval($status),
            "view" => 0,
            "brand_id" => $brandId,
            "guaranty_id" => $guaranty_id,
            "meta_title" => $metaTitle,
            "meta_description" => $metaDescription,
        ]);
        $this->productCategoryRepository->create([
            "product_id" => $product->id,
            "category_id" => $categoryId
        ]);
        return $product;
    }

    public function updateProduct($id, $name, $url, $description, $study, $status, $categoryId, $brandId, $metaTitle, $metaDescription, $guaranty_id): mixed
    {
        $product = $this->productRepository->findOrFail($id);
        $this->productRepository->update($product,
            [
                "name" => $name,
                "url" => $url,
                "description" => $description,
                "study" => $study,
                "status" => intval($status),
                "brand_id" => $brandId,
                "meta_title" => $metaTitle,
                "guaranty_id" => $guaranty_id,
                "meta_description" => $metaDescription,
            ]);
        $this->productCategoryRepository->updateWithProductId($id, $categoryId);
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
        return $this->productRepository->getByCategoryId($productCategory->category_id);
    }

    public function setVideo($productId, $file, $type): mixed
    {
        $product = $this->productRepository->findOrFail($productId);
        switch ($type) {
            case "intro":
                $videoPath = $product->intro_video;
                $this->s3Service->remove("product/video/intro/$videoPath");
                $videoPath = $this->s3Service->upload($file, "product/video/intro");
                $this->productRepository->update($product, ["intro_video" => $videoPath]);
                return true;
            case "unboxing":
                $videoPath = $product->intro_video;
                $this->s3Service->remove("product/video/unboxing/$videoPath");
                $videoPath = $this->s3Service->upload($file, "product/video/unboxing");
                $this->productRepository->update($product, ["unboxing_video" => $videoPath]);
                return true;
            case "usage":
                $videoPath = $product->intro_video;
                $this->s3Service->remove("product/video/usage/$videoPath");
                $videoPath = $this->s3Service->upload($file, "product/video/usage");
                $this->productRepository->update($product, ["usage_video" => $videoPath]);
                return true;
        }
        throw new BreakException(\Lang::get("exceptions.type_not_find"));
    }
}
