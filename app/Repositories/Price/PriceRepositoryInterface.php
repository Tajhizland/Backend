<?php

namespace App\Repositories\Price;

use App\Repositories\Base\BaseRepositoryInterface;

interface PriceRepositoryInterface extends BaseRepositoryInterface
{
    public function createPrice($productColorId, $price, $discount,$discountExpireTime);

    public function updatePrice($productColorId, $price, $discount,$discountExpireTime);

    public function findByProductColorId($productColorId);
}
