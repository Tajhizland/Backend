<?php

namespace App\Repositories\ChatInfo;

use App\Repositories\Base\BaseRepositoryInterface;

interface ChatInfoRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUserId($userId);
}
