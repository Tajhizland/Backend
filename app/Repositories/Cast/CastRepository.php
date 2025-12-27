<?php

namespace App\Repositories\Cast;

use App\Models\Cast;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class CastRepository extends BaseRepository implements CastRepositoryInterface
{
    public function __construct(Cast $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Cast::class)
            ->allowedFilters(['id', 'image', 'title', 'url', 'category_id', 'status'])
            ->allowedSorts(['id', 'image', 'title', 'url', 'category_id', 'status'])
            ->latest("id")
            ->paginate($this->pageSize);
    }

    public function findWithVlog($id)
    {
        return $this->model::with("vlog")->findOrFail($id);
    }

    public function findByUrl($url)
    {
        return $this->model::with("vlog")->where('url', $url)->where("status", 1)->firstOrFail();
    }
}
