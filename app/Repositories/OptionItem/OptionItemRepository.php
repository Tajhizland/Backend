<?php

namespace App\Repositories\OptionItem;

use App\Models\OptionItem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class OptionItemRepository extends BaseRepository implements OptionItemRepositoryInterface
{
    public function __construct(OptionItem $model)
    {
        parent::__construct($model);
    }

    public function createFilterItem($optionId, $title, $status, $sort = null)
    {
        return $this->create([
            "option_id" => $optionId,
            "title" => $title,
            "status" => $status,
            "sort" => $sort,
        ]);
    }

    public function updateFilterItem(OptionItem $optionItem, $title, $status)
    {
        return $optionItem->update(
            [
                "title" => $title,
                "status" => $status,
            ]
        );
    }

    public function find($id)
    {
        return $this->model::find($id);
    }

    public function findLastSortOfOption_id($optionId)
    {
        return $this->model::where("option_id", $optionId)->latest("sort")->first();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function getByOptionId($id)
    {
        return $this->model::where("option_id", $id)->orderBy("sort")->get();
    }
}
