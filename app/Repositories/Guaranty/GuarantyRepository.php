<?php

namespace App\Repositories\Guaranty;

use App\Models\Guaranty;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class GuarantyRepository extends BaseRepository implements GuarantyRepositoryInterface
{
    public function __construct(Guaranty $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Guaranty::class)
            ->allowedFilters(['name', 'description', 'status', 'id', 'created_at'])
            ->allowedSorts(['name', 'description', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function getActives()
    {
        return $this->model::active()->latest("id")->get();
    }

    public function findByUrl($url)
    {
        return $this->model::active()->where("url", $url)->first();
    }
}
