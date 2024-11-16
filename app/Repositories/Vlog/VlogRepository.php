<?php

namespace App\Repositories\Vlog;

use App\Models\Vlog;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class VlogRepository extends BaseRepository implements VlogRepositoryInterface
{
    public function __construct(Vlog $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Vlog::class)
            ->allowedFilters(['title', 'description', 'status', 'id', 'created_at'])
            ->allowedSorts(['title', 'description', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findByUrl($url)
    {
        return $this->model::active()->where("url",$url)->first();
    }

    public function listing()
    {
        return $this->model::active()->latest("id")->paginate($this->pageSize);
    }
    public function getLastActives()
    {
        return $this->model::active()->latest("id")->limit(4)->get();
    }
}
