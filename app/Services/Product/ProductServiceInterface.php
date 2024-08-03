<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    public function findProductByUrl(string $url):mixed;
    public function getPaginatedFilterable():mixed;
    public function findById($id):mixed;
    public function storeProduct($name , $url , $description , $study , $categoryId , $colors):mixed;
    public function updateProduct($id,$name , $url , $description , $study , $categoryId , $colors):mixed;
}
