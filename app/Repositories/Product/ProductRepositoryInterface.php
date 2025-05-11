<?php

namespace App\Repositories\Product;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUrl($url);

    public function findById($id);
    public function getByCategoryId($id ,$except);
    public function getAllByCategoryId($id);
    public function getSpecial();
    public function sort($id , $sort);

    public function dataTable();
    public function searchProductWithCategory($query , $categoryId);
     public function incrementViewCount($product);
    public function customPaginate($perPage);

    public function search($query);
    public function searchPaginate($query);

    public function showFavoriteList($userId);

    public function activeProductQuery($categoryIds);
    public function activeProductByBrandQuery($brandId);

    public function paginated($query);

    public function createProduct($name, $url, $description, $study ,$status, $brandId , $metaTitle , $metaDescription);
    public function updateProduct($id,$name, $url, $description, $study ,$status, $brandId , $metaTitle , $metaDescription);


    public function getCustomCategoryProduct($categoryId);
    public function getNewProduct();
    public function getHasDiscountProduct();
    public function getMostPopularProduct();
    public function getDiscountedProducts();
    public function getDiscountedProductsId();
    /***Filters***/

    public function categoryFilters($query, $categoryId);
    public function categoryFilter($query, $categoryId);

    public function minPriceFilter($query, $minPrice);

    public function maxPriceFilter($query, $maxPrice);

    public function nameFilter($query, $name);

    public function hasStockFilter($query);

    public function otherFilter($key, $values, $query);

    /***End Filters***/


    public function getSitemapData();
    public function getTorobProducts();
}
