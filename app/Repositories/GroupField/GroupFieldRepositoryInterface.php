<?php

namespace App\Repositories\GroupField;

use App\Repositories\Base\BaseRepositoryInterface;

interface GroupFieldRepositoryInterface extends BaseRepositoryInterface
{
    public function getByGroupId($groupId);
}
