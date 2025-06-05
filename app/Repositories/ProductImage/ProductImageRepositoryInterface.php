<?php

namespace App\Repositories\ProductImage;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductImageRepositoryInterface extends  BaseRepositoryInterface
{
    public function getByProductId($productId);
    public function sort($id,$sort);
}
