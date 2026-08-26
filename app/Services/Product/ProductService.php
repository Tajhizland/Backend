<?php

namespace App\Services\Product;

use App\DTOs\Product\ProductGroupDigipayDto;
use App\DTOs\Product\ProductGroupPercentDto;
use App\DTOs\Product\ProductGroupPriceDto;
use App\DTOs\Product\ProductGroupSnappayDto;
use App\DTOs\Product\ProductGroupStatusDto;
use App\DTOs\Product\ProductGroupStockDto;
use App\DTOs\Product\ProductSearchListDto;
use App\DTOs\Product\ProductSetVideo2Dto;
use App\DTOs\Product\ProductSetVideoDto;
use App\DTOs\Product\ProductStoreDto;
use App\DTOs\Product\ProductUpdateDto;

use App\Exceptions\BreakException;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repositories\ProductVideo\ProductVideoRepositoryInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\ProductCategory\ProductCategoryServiceInterface;
use App\Services\ProductGuaranty\ProductGuarantyServiceInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class ProductService implements ProductServiceInterface
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

    public function getStockProducts($filter): mixed
    {
        $productsQuery = $this->productRepository->getStockProducts();
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

    public function stockDataTable(): mixed
    {
        return $this->productRepository->stockDataTable();
    }

    public function searchProduct($query): mixed
    {
        return $this->productRepository->search($query);
    }

    public function find(int $id): mixed
    {
        return $this->productRepository->findById($id);
    }

    public function storeProduct(ProductStoreDto $dto): mixed
    {
        $name = $dto->name; $url = $dto->url; $description = $dto->description; $study = $dto->study;
        $status = $dto->status; $categoryId = $dto->categoryId; $brandId = $dto->brand_id;
        $metaTitle = $dto->meta_title; $metaDescription = $dto->meta_description;
        $guaranty_id = $dto->guaranty_id; $guaranty_time = $dto->guaranty_time; $review = $dto->review;
        $type = $dto->type; $is_stock = $dto->is_stock; $testing_time = $dto->testing_time;
        $stock_of = $dto->stock_of; $length = $dto->length; $width = $dto->width;
        $height = $dto->height; $weight = $dto->weight; $use_packet = $dto->use_packet;
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
            "is_stock" => $is_stock,
            "guaranty_time" => $guaranty_time,
            "meta_title" => $metaTitle,
            "meta_description" => $metaDescription,
            "testing_time" => $testing_time,
            "stock_of" => $stock_of,
            "use_packet" => $use_packet,
            "length" => $length,
            "width" => $width,
            "height" => $height,
            "weight" => $weight,
        ]);
        $categoryIds = json_decode($categoryId);
        $this->productCategoryService->syncProductCategory($product->id, $categoryIds);
        $guarantyIds = json_decode($guaranty_id);
        $this->productGuarantyService->syncProductGuaranty($product->id, $guarantyIds);
        return $product;
    }

    public function updateProduct(ProductUpdateDto $dto): mixed
    {
        $id = $dto->productId; $name = $dto->name; $url = $dto->url; $description = $dto->description;
        $study = $dto->study; $status = $dto->status; $categoryId = $dto->categoryId; $brandId = $dto->brand_id;
        $metaTitle = $dto->meta_title; $metaDescription = $dto->meta_description;
        $guaranty_id = $dto->guaranty_id; $guaranty_time = $dto->guaranty_time; $review = $dto->review;
        $type = $dto->type; $is_stock = $dto->is_stock; $testing_time = $dto->testing_time;
        $stock_of = $dto->stock_of; $length = $dto->length; $width = $dto->width;
        $height = $dto->height; $weight = $dto->weight; $use_packet = $dto->use_packet;
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
                "is_stock" => $is_stock,
                "meta_description" => $metaDescription,
                "testing_time" => $testing_time,
                "stock_of" => $stock_of,
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "weight" => $weight,
            "use_packet" => $use_packet,

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

    public function setVideo(ProductSetVideoDto $dto): mixed
    {
        $productId = $dto->productId; $vlogId = $dto->vlogId; $type = $dto->type;
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


    public function torobProduct($page_urls, $page_uniques, $page, $sort)
    {
        if ($page_urls) {
            $urls=[];
            foreach ($page_urls as $url)
            {
                $urls[]=str_replace("https://tajhizland.com/product/", "", $url);
            }
            $response = $this->productRepository->getTorobProductsWithUrls($urls);
        } else if ($page_uniques) {
            $response = $this->productRepository->getTorobProductsWithIds($page_uniques);
        } else {
            $response = $this->productRepository->getTorobProducts();
        }
        return $response;
    }
    public function setVideo2(ProductSetVideo2Dto $dto): mixed
    {
        $productId = $dto->product_id; $vlogId = $dto->vlogId; $title = $dto->title;
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

    public function searchList(ProductSearchListDto $dto): mixed
    {
        $categoryId = $dto->categoryId; $brandId = $dto->brandId; $searchQuery = $dto->searchQuery; $discountId = $dto->discountId ?? 0;
        return $this->productRepository->searchList($categoryId, $brandId, $searchQuery, $discountId);
    }

    public function groupChangePrice(ProductGroupPriceDto $dto): mixed
    {
        $ids = $dto->ids; $action = $dto->action; $percent = $dto->percent;
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $colors = $product->productColors;
            if ($colors) {
                foreach ($colors as $color) {
                    $price = $color->price;
                    $currentPrice = $price->price;
                    $newPrice = $currentPrice;
                    if ($action == "inc")
                        $newPrice = $currentPrice + ($currentPrice * $percent / 100);
                    if ($action == "dec")
                        $newPrice = $currentPrice - ($currentPrice * $percent / 100);

                    $price->price = $newPrice;
                    $price->save();
                }
            }
        }
        return true;
    }

    public function groupChangeStock(ProductGroupStockDto $dto): mixed
    {
        $ids = $dto->ids; $stock = $dto->stock;
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $colors = $product->productColors;
            if ($colors) {
                foreach ($colors as $color) {
                    $colorStock = $color->stock;
                    $colorStock->stock = $stock;
                    $colorStock->save();
                }
            }
        }
        return true;
    }

    public function groupChangeStatus(ProductGroupStatusDto $dto): mixed
    {
        $ids = $dto->ids; $status = $dto->status;
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $colors = $product->productColors;
            if ($colors) {
                foreach ($colors as $color) {
                    $color->status = $status;
                    $color->save();
                }
            }
        }
        return true;
    }

    public function groupChangeDigipay(ProductGroupDigipayDto $dto): mixed
    {
        $ids = $dto->ids; $digipay = $dto->digipay;
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $product->allow_digipay = $digipay;
            $product->save();
        }
        return true;
    }

    public function groupChangeSnappay(ProductGroupSnappayDto $dto): mixed
    {
        $ids = $dto->ids; $snappay = $dto->snappay;
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $product->allow_snappay = $snappay;
            $product->save();
        }
        return true;
    }
   public function groupChangeDigipayPercent(ProductGroupPercentDto $dto): mixed
    {
        $ids = $dto->ids; $percent = $dto->percent;
        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $product->digipay_extra_price = $percent;
            $product->save();
        }
        return true;
    }
}
