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

interface ProductServiceInterface
{
    public function findProductByUrl(string $url): mixed;

    public function dataTable(): mixed;

    public function stockDataTable(): mixed;

    public function hasLimitDataTable(): mixed;

    public function hasDiscountDataTable(): mixed;

    public function searchProductWithCategory($query, $categoryId): mixed;

    public function searchProduct($query): mixed;

    public function find(int $id): mixed;

    public function special(): mixed;

    public function getRelatedProducts($id): mixed;

    public function storeProduct(ProductStoreDto $dto): mixed;

    public function updateProduct(ProductUpdateDto $dto): mixed;

    public function setVideo(ProductSetVideoDto $dto): mixed;

    public function setVideo2(ProductSetVideo2Dto $dto): mixed;

    public function deleteVideo($id);

    public function getVideo($productId);

    public function getDiscountedProducts($filter): mixed;

    public function getStockProducts($filter): mixed;

    public function getSitemapData();

    public function customPaginate($perPage);

    public function torobProduct($page_urls,$page_uniques,$page,$sort);

    public function searchList(ProductSearchListDto $dto): mixed;

    public function groupChangePrice(ProductGroupPriceDto $dto): mixed;
    public function groupChangeStock(ProductGroupStockDto $dto): mixed;
    public function groupChangeStatus(ProductGroupStatusDto $dto): mixed;
    public function groupChangeDigipay(ProductGroupDigipayDto $dto): mixed;
    public function groupChangeSnappay(ProductGroupSnappayDto $dto): mixed;
    public function groupChangeDigipayPercent(ProductGroupPercentDto $dto): mixed;

}
