<?php

namespace App\Repositories\VlogCategory;

use App\Models\VlogCategory;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class VlogCategoryRepository extends BaseRepository implements VlogCategoryRepositoryInterface
{
    public function __construct(VlogCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(VlogCategory::class)
            ->allowedFilters(['name', 'status', 'id', 'created_at'])
            ->allowedSorts(['title', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function getActiveList()
    {
        return $this->model::active()->get();
    }
}
