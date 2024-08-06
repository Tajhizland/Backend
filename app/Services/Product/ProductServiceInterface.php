<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    public function findProductByUrl(string $url):mixed;
    public function dataTable():mixed;
    public function findById($id):mixed;
    public function storeProduct($name , $url , $description , $study ,$status, $categoryId , $brandId , $metaTitle , $metaDescription , $colors):mixed;
    public function updateProduct($id,$name , $url , $description , $study , $status,$categoryId , $brandId , $metaTitle , $metaDescription, $colors):mixed;
}
