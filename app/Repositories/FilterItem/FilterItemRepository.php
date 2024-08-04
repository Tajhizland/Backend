<?php

namespace App\Repositories\FilterItem;

use App\Models\FilterItem;
use App\Repositories\Base\BaseRepository;

class FilterItemRepository extends BaseRepository implements FilterItemRepositoryInterface
{
    public function __construct(FilterItem $model)
    {
        parent::__construct($model);
    }

    public function createFilterItem($filterId, $value, $status)
    {
        return $this->create([
            "filter_id" => $filterId,
            "value" => $value,
            "status" => $status,
        ]);
    }

    public function updateFilterItem(FilterItem $filterItem , $value, $status)
    {
        return $filterItem ->update(
                [
                    "value" => $value,
                    "status" => $status,
                ]
            );
    }
}
