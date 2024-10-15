<?php

namespace App\Services\ProductImage;

interface ProductImageServiceInterface
{
    public function getByProductId($productId);
    public function create($productId, $image);
    public function remove($id);
}
