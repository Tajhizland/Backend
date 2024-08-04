<?php

namespace App\Repositories\Product;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUrl($url);

    public function findById($id);

    public function dateTable();

    public function incrementViewCount($product);

    public function search($query);

    public function showFavoriteList($userId);

    public function activeProductQuery();

    public function paginated($query);

    public function createProduct($name, $url, $description, $study ,$status);
    public function updateProduct($id,$name, $url, $description, $study ,$status);

    /***Filters***/

    public function minPriceFilter($query, $minPrice);

    public function maxPriceFilter($query, $maxPrice);

    public function nameFilter($query, $name);

    public function hasStockFilter($query);

    public function otherFilter($key, $values, $query);

    /***End Filters***/

}
