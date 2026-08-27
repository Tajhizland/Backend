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

    /**
     * چند نوع بنر را با یک کوئری می‌گیرد و بر اساس type گروه‌بندی می‌کند.
     *
     * برای صفحاتی مثل صفحه اصلی که به ۷ نوع بنر نیاز دارند و قبلا ۷ کوئری جدا می‌زدند.
     *
     * @param string[] $types
     * @return \Illuminate\Support\Collection<string, \Illuminate\Database\Eloquent\Collection>
     */
    public function getGroupedByTypes(array $types)
    {
        return $this->model::select("id", "image", "url", "type", "sort", "created_at", "updated_at")
            ->whereIn("type", $types)
            ->orderBy("sort")
            ->get()
            ->groupBy("type");
    }
}
