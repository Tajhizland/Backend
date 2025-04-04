<?php

namespace App\Repositories\Price;

use App\Repositories\Base\BaseRepositoryInterface;

interface PriceRepositoryInterface extends BaseRepositoryInterface
{
    public function createPrice($productColorId, $price, $discount,$discountExpireTime=null);

    public function updatePrice($productColorId, $price, $discount,$discountExpireTime=null);

    public function findByProductColorId($productColorId);

    public function findFirstExpireDiscount();
}
