<?php

namespace App\Repositories\CategoryViewHistory;

use App\Repositories\Base\BaseRepositoryInterface;

interface CategoryViewHistoryRepositoryInterface extends BaseRepositoryInterface
{
    public function findTop($userId);
}
