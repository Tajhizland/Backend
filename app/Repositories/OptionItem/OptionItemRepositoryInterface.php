<?php

namespace App\Repositories\OptionItem;

use App\Models\OptionItem;
use App\Repositories\Base\BaseRepositoryInterface;

interface OptionItemRepositoryInterface extends BaseRepositoryInterface
{
    public function createFilterItem($optionId, $title, $status);

    public function updateFilterItem(OptionItem $optionItem, $title, $status);
}
