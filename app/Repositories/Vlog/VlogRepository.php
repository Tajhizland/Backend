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
        return $this->model::active()->orderBy("sort");
    }

    public function filterCategory($query, $categoryIds)
    {
        return $query->whereIn("category_id", $categoryIds);
    }

    public function getLastActives()
    {
        return $this->model::active()->latest("id")->whereIn("category_id", [1, 2, 4])->limit(4)->get();
    }

    public function getHomePageVlogs()
    {
        return $this->model::with('homePage')
            ->whereHas('homePage')
            ->get()
            ->sortBy(fn($item) => optional($item->homePage)->id)
            ->values();

    }

    public function paginated($query)
    {
        return $query->paginate($this->pageSize);
    }

    public function filterTitle($query, $title)
    {
        $keywords = explode(' ', $title);

        return $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->where('title', 'like', '%' . $word . '%');
            }
        });
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
        return $this->model::where("category_id", $category_id)->where("id", "<>", $except)->where("status", 1)->limit(4)->latest("id")->get();
    }

    public function getSitemapData()
    {
        return $this->model::active()->select("url")->latest("id")->get();
    }

    public function getMostViewed()
    {
        return $this->model::active()->orderBy("view", "desc")->latest("id")->limit(5)->get();

    }

    public function search($query)
    {
        return $this->model::active()->where("title", "like", "%$query%")->latest("id")->limit(20)->get();

    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function activeList()
    {
        return $this->model::active()->orderBy("sort")->get();
    }

    public function getByCategory($categoryId)
    {
        return $this->model::active()->where("category_id", $categoryId)->get();
    }
}
