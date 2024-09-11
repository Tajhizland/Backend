<?php

namespace App\Services\ProductColor;

interface ProductColorServiceInterface
{
    public function getByProductId($productId);
    public function setProductColor($productId , $colors);
}
