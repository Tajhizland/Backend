<?php

namespace App\Services\ProductColor;

use App\DTOs\ProductColor\ProductColorFastUpdateDto;
use App\DTOs\ProductColor\ProductColorSetDto;

interface ProductColorServiceInterface
{
    public function getByProductId($productId);
    public function setProductColor(ProductColorSetDto $dto): mixed;
    public function colorFastUpdate(ProductColorFastUpdateDto $dto): mixed;
}
