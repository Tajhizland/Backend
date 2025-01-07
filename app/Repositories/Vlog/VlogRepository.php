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
        return $this->model::active()->where("url", $url)->first();
    }

    public function activeVlogQuery()
    {
        return $this->model::active();
    }

    public function filterCategory($query, $categoryIds)
    {
        return $query->whereIn("category_id", $categoryIds);
    }

    public function getLastActives()
    {
        return $this->model::active()->latest("id")->limit(4)->get();
    }

    public function paginated($query)
    {
        return $query->paginate($this->pageSize);
    }

    public function filterTitle($query, $title)
    {
        return $query->where("title", 'like', '%' . $title . '%');
    }

    public function sortView($query)
    {
        return $query->orderBy("view", "desc");
    }

    public function sortNew($query)
    {
        return $query->orderBy("id", "desc");
    }

    public function sortOld($query)
    {
        return $query->orderBy("id");
    }

    public function getRelatedVlogs($category_id, $except)
    {
        return $this->model::where("category_id", $category_id)->where("id", "<>", $except)->limit(4)->latest("id")->get();
    }
    public function getSitemapData()
    {
        return $this->model::active()->select("url")->latest("id")->get();
    }
}
