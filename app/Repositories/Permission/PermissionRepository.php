<?php

namespace App\Repositories\Permission;

use App\Models\Permission;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Permission::class)
            ->allowedFilters(['id', 'name', 'value', 'created_at', 'updated_at'])
            ->allowedSorts(['id', 'name', 'value', 'created_at', 'updated_at'])
            ->latest("id")
            ->paginate($this->pageSize);
    }
}
