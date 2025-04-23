<?php

namespace App\Repositories\ProductVideo;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductVideoRepositoryInterface extends  BaseRepositoryInterface
{
    public function getByProductId($productId);
}
