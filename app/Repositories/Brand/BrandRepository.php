<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Brand::class)
            ->allowedFilters(['name', 'url', 'status', 'description', 'id', 'created_at', 'parent_id'])
            ->allowedSorts(['name', 'url', 'status', 'id', 'description', 'created_at', 'parent_id'])
            ->paginate($this->pageSize);
    }

    public function list()
    {
        return $this->model::active()->select("id", "name")->orderBy("sort")->get();
    }
    public function findByUrl($url)
    {
        return $this->model::where('url', $url)->first();
    }
    public function getAllActive(?int $limit = null)
    {
        $query = $this->model::active()->orderBy("sort");

        if ($limit !== null && $limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);

    }
    public function getSitemapData()
    {
        return $this->model::active()->select("url")->latest("id")->get();
    }
}
