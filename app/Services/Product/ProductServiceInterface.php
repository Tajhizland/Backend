<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    public function findProductByUrl(string $url):mixed;
    public function dataTable():mixed;
    public function searchProductWithCategory($query , $categoryId):mixed;
    public function searchProduct($query):mixed;
    public function findById($id):mixed;
    public function special():mixed;
    public function getRelatedProducts($id):mixed;
    public function storeProduct($name , $url , $description , $study ,$status, $categoryId , $brandId , $metaTitle , $metaDescription,$guaranty_id ):mixed;
    public function updateProduct($id,$name , $url , $description , $study , $status,$categoryId , $brandId , $metaTitle , $metaDescription ,$guaranty_id):mixed;
    public function setVideo($productId,$file,$type):mixed;
    public function getDiscountedProducts():mixed;
}
