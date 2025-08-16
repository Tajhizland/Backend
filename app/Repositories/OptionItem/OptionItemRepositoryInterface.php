<?php

namespace App\Repositories\OptionItem;

use App\Models\OptionItem;
use App\Repositories\Base\BaseRepositoryInterface;

interface OptionItemRepositoryInterface extends BaseRepositoryInterface
{

    public function updateFilterItem(OptionItem $optionItem, $title, $status);

    public function find($id);
    public function getByOptionId($id);

    public function sort($id, $sort);

    public function findLastSortOfCategory($categoryId);
    public function getCategoryOptions($categoryId);
}
