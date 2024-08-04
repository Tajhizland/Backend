<?php

namespace App\Repositories\Option;

use App\Models\Option;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class OptionRepository extends BaseRepository implements OptionRepositoryInterface
{

    public function createOption($title, $categoryId, $status)
    {
        $this->create([
            "title" => $title,
            "category_id" => $categoryId,
            "status" => $status,
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
}
