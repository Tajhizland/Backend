<?php

namespace App\Services\ProductImage;

interface ProductImageServiceInterface
{
    public function getByProductId($productId);
    public function upload($productId, $image);
    public function upload2($productId, $image);
    public function remove($id);
    public function sort($array);
}
