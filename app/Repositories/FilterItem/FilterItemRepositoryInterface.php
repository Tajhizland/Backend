<?php

namespace App\Repositories\FilterItem;

use App\Models\FilterItem;
use App\Repositories\Base\BaseRepositoryInterface;

interface FilterItemRepositoryInterface extends BaseRepositoryInterface
{
    public function createFilterItem($filterId, $value, $status);

    public function updateFilterItem(FilterItem $filterItem, $value, $status);
}
