<?php

namespace App\Repositories\OptionItem;

use App\Models\OptionItem;
use App\Repositories\Base\BaseRepositoryInterface;

interface OptionItemRepositoryInterface extends BaseRepositoryInterface
{
    public function createFilterItem($optionId, $title, $status,$sort=null);

    public function updateFilterItem(OptionItem $optionItem, $title, $status);

    public function find($id);
    public function getByOptionId($id);

    public function sort($id, $sort);

    public function findLastSortOfOption_id($optionId);


}
