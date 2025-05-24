<?php

namespace App\Repositories\GroupProduct;

use App\Repositories\Base\BaseRepositoryInterface;

interface GroupProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByGroupAndProduct($productId, $groupId);

    public function getByGroupId($groupId);
    public function getByGroupIdWithValue($groupId);
}
