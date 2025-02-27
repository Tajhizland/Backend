<?php

namespace App\Repositories\Poster;

use App\Models\Poster;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class PosterRepository extends BaseRepository implements PosterRepositoryInterface
{
    public function __construct(Poster $model)
    {
        parent::__construct($model);
    }
    public function dataTable()
    {
        return QueryBuilder::for(Poster::class)
            ->allowedFilters(['id','created_at'])
            ->allowedSorts(['id','created_at'])
            ->paginate($this->pageSize);
    }
    public function getHomepagePosters()
    {
        return $this->model::whereIn("id",[1,2])->get();
    }
}
