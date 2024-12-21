<?php

namespace App\Repositories\ProductGuaranty;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductGuarantyRepositoryInterface extends  BaseRepositoryInterface
{
    public function deleteByProductId($productId);
}
