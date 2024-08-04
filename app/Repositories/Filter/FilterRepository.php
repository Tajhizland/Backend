<?php

namespace App\Repositories\Filter;

use App\Models\Category;
use App\Models\Filter;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class FilterRepository extends BaseRepository implements FilterRepositoryInterface
{

    public function __construct(Filter $model)
    {
        parent::__construct($model);
    }

    public function createFilter($name, $categoryId, $status, $type)
    {
        $this->create([
            "name" => $name,
            "category_id" => $categoryId,
            "status" => $status,
            "type" => $type,
        ]);
    }

    public function updateFilter($id, $name, $categoryId, $status, $type)
    {
        return $this->model::where("id", $id)
            ->update(
                [
                    "name" => $name,
                    "category_id" => $categoryId,
                    "status" => $status,
                    "type" => $type,
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
}
