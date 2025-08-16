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

    public function findLastSortOfCategory($categoryId){
        return $this->model::where("category_id", $categoryId)->latest("sort")->first();

    }
    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function getByOptionId($id)
    {
        return $this->model::where("option_id", $id)->orderBy("sort")->get();
    }

    public function getCategoryOptions($categoryId)
    {
        return $this->model::where("category_id", $categoryId)->orderBy("sort")->get();

    }

    public function getByProductId($productId)
    {
        return $this->model::whereHas("category", function ($query) use ($productId) {
            $query->whereHas("productCategory", function ($query2) use ($productId) {
                $query2->where("product_id", $productId);
            });
        })->with(["productOption" => function ($query2) use ($productId) {
            $query2->where("product_id", $productId);
        }])->get();
    }
}
