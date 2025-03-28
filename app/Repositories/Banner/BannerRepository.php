<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Banner::class)
            ->allowedFilters(['url', 'id', 'type', 'created_at', 'updated_at'])
            ->allowedSorts(['url', 'id', 'type', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function getAll()
    {
        return $this->model::orderBy("sort")->get();
    }

    public function getBannerByType($type)
    {
        return $this->model::where("type", $type)->orderBy("sort")->get();
    }
}
