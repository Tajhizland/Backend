<?php

namespace App\Repositories\DiscountItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface DiscountItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getByDiscountId($discountId);

    public function getTopByDiscountId($discountId);

    public function findFirstExpireDiscount();

    public function sort($id, $sort);

    public function findByProductColorId($discountId, $productColorId);
}
