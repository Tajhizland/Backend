<?php

namespace App\Repositories\CastCategory;

use App\Models\CastCategory;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class CastCategoryRepository extends BaseRepository implements CastCategoryRepositoryInterface
{
    public function __construct(CastCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(CastCategory::class)
            ->allowedFilters(['name', 'status', 'id', 'created_at'])
            ->allowedSorts(['name', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function getActives()
    {
        return $this->model::where('status', 1)->get();
    }
}
