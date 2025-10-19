<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    public function findProductByUrl(string $url): mixed;

    public function dataTable(): mixed;
    public function stockDataTable(): mixed;

    public function hasLimitDataTable(): mixed;

    public function hasDiscountDataTable(): mixed;

    public function searchProductWithCategory($query, $categoryId): mixed;

    public function searchProduct($query): mixed;

    public function findById($id): mixed;

    public function special(): mixed;

    public function getRelatedProducts($id): mixed;

    public function storeProduct($name, $url, $description, $study, $status, $categoryId, $brandId, $metaTitle, $metaDescription, $guaranty_id, $guaranty_time, $review, $type, $is_stock): mixed;

    public function updateProduct($id, $name, $url, $description, $study, $status, $categoryId, $brandId, $metaTitle, $metaDescription, $guaranty_id, $guaranty_time, $review, $type, $is_stock): mixed;

    public function setVideo($productId, $vlogId, $type): mixed;

    public function setVideo2($productId, $vlogId, $title);

    public function deleteVideo($id);

    public function getVideo($productId);

    public function getDiscountedProducts($filter): mixed;
    public function getStockProducts($filter): mixed;

    public function getSitemapData();

    public function customPaginate($perPage);

    public function torobProduct();

    public function searchList($categoryId, $brandId): mixed;

    public function groupChangePrice($ids, $action, $percent);
}
