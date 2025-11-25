<?php

namespace App\Repositories\DiscountItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface DiscountItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getByDiscountId($discountId);
    public function findByProductColorId($discountId , $productColorId);
}
