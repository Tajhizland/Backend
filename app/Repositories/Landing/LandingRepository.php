<?php

namespace App\Repositories\Landing;

use App\Models\Landing;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class LandingRepository extends BaseRepository implements LandingRepositoryInterface
{
    public function __construct(Landing $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Landing::class)
            ->allowedFilters(['title', 'url', 'status', 'id', 'created_at'])
            ->allowedSorts(['title', 'url', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findByUrl($url)
    {
        return $this->model::active()
            ->where("url", $url)
            ->with(["products", "categories"])
            ->first();
    }
}
