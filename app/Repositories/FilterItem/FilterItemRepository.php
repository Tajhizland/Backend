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
        $lastSort = $this->findLastSortOfFilter($filterId);

        return $this->create([
            "filter_id" => $filterId,
            "value" => $value,
            "status" => $status,
            "sort" => ($lastSort->sort ?? 0) + 1,
        ]);
    }

    public function findLastSortOfFilter($filterId)
    {
        return $this->model::where("filter_id", $filterId)->latest("sort")->first();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function getByFilterId($filterId)
    {
        return $this->model::where("filter_id", $filterId)->orderBy("sort")->get();
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
