<?php

namespace App\Repositories\Filter;

use App\Models\Filter;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class FilterRepository extends BaseRepository implements FilterRepositoryInterface
{

    public function __construct(Filter $model)
    {
        parent::__construct($model);
    }

    public function createFilter($name, $categoryId, $status)
    {
       return $this->create([
            "name" => $name,
            "category_id" => $categoryId,
            "status" => $status,
        ]);
    }

    public function updateFilter($id, $name, $categoryId, $status)
    {
        return $this->model::where("id", $id)
            ->update(
                [
                    "name" => $name,
                    "category_id" => $categoryId,
                    "status" => $status,
                ]
            );
    }

    public function dataTable()
    {
        return QueryBuilder::for(Filter::class)
            ->select("filters.*")
            ->allowedFilters(['name', 'category_id', 'status', 'type', 'created_at', 'updated_at'])
            ->allowedSorts(['name', 'category_id', 'status', 'type', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }

    public function getByProductId($productId)
    {
        return $this->model::whereHas("category", function ($query) use ($productId) {
            $query->whereHas("productCategory", function ($query2) use ($productId) {
                $query2->where("product_id", $productId);
            });
        })
            ->with(["items"])
            ->with(["productFilters" => function ($query) use ($productId) {
                $query->where("product_id", $productId);
            }])->get();
    }
    public function getCategoryFilters($categoryId)
    {
        return $this->model::where("category_id", $categoryId)->with("items")->get();
    }
    public function find($id)
    {
        return $this->model::find($id);
    }
}
