<?php

namespace App\Repositories\Option;

use App\Models\Option;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

class OptionRepository extends BaseRepository implements OptionRepositoryInterface
{

    public function __construct(Option $model)
    {
        parent::__construct($model);
    }

    public function createOption($title, $categoryId, $status, $sort = null)
    {
        return $this->create([
            "title" => $title,
            "category_id" => $categoryId,
            "status" => $status,
            "sort" => $sort,
        ]);
    }

    public function updateOption($id, $title, $categoryId, $status)
    {
        return $this->model::where("id", $id)
            ->update(
                [
                    "title" => $title,
                    "category_id" => $categoryId,
                    "status" => $status,
                ]
            );
    }

    public function dataTable()
    {
        return QueryBuilder::for(Option::class)
            ->select("options.*")
            ->allowedFilters(['title', 'category_id', 'status', 'created_at', 'updated_at'])
            ->allowedSorts(['title', 'category_id', 'status', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }

    public function getByProductId($productId)
    {
        return $this->model::whereHas("category", function ($query) use ($productId) {
            $query->whereHas("productCategory", function ($query2) use ($productId) {
                $query2->where("product_id", $productId);
            });
        })->with(["optionItems" => function ($query) use ($productId) {
            $query->with(["productOption" => function ($query2) use ($productId) {
                $query2->where("product_id", $productId);
            }]);
        }])->get();
    }

    public function getCategoryOptions($categoryId)
    {
        return $this->model::where("category_id", $categoryId)->with("optionItems")->get();
    }

    public function find($id)
    {
        return $this->model::find($id);
    }

    public function findLastSortOfCategory($categoryId)
    {
        return $this->model::where("category_id", $categoryId)->latest("sort")->first();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }
}
