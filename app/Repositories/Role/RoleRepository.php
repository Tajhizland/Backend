<?php

namespace App\Repositories\Role;

use App\Models\Role;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Role::class)
            ->allowedFilters(['id', 'name', 'created_at', 'updated_at'])
            ->allowedSorts(['id', 'name', 'created_at', 'updated_at'])
            ->latest("id")
            ->paginate($this->pageSize);
    }
}
