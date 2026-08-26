<?php

namespace App\Services\ProductImage;

use App\DTOs\ProductImage\ProductImageSetColorDto;
use App\DTOs\ProductImage\ProductImageSortDto;
use App\DTOs\ProductImage\ProductImageUploadDto;

interface ProductImageServiceInterface
{
    public function getByProductId($productId);
    public function upload(ProductImageUploadDto $dto): mixed;
    public function upload2($productId, $image);
    public function remove($id);
    public function sort(ProductImageSortDto $dto): mixed;
    public function setColor(ProductImageSetColorDto $dto): mixed;
}
