<?php

namespace App\Repositories\BlogCategory;

use App\Models\BlogCategory;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class BlogCategoryRepository extends BaseRepository implements BlogCategoryRepositoryInterface
{
    public function __construct(BlogCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(BlogCategory::class)
            ->allowedFilters(['url', 'id', 'status', 'name', 'created_at', 'updated_at'])
            ->allowedSorts(['url', 'id', 'status', 'name', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }

    public function getActiveList()
    {
        return $this->model::active()->get();
    }
}
