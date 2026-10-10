<?php

namespace App\Repositories\InstagramPost;

use App\Models\InstagramPost;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class InstagramPostRepository extends BaseRepository implements InstagramPostRepositoryInterface
{
    public function __construct(InstagramPost $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(InstagramPost::class)
            ->withCount("media")
            ->allowedFilters(...['caption', 'status', 'id', 'created_at'])
            ->allowedSorts(...['caption', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findWithMedia(int $id)
    {
        return $this->model::with("media")->find($id);
    }

    public function findActiveWithMedia(int $id)
    {
        return $this->model::active()->with("media")->find($id);
    }

    /**
     * فید عمومی. رسانه‌ها eager load می‌شوند تا هر پست یک کوئری اضافه نزند
     * (هر پست چند فایل دارد، بدون این کار N+1 چند برابر می‌شود).
     */
    public function activeQuery()
    {
        return $this->model::active()->with("media")->orderBy("sort")->orderBy("id", "desc");
    }

    public function filterCaption($query, $caption)
    {
        $keywords = explode(' ', $caption);

        return $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->where('caption', 'like', '%' . $word . '%');
            }
        });
    }

    public function paginated($query)
    {
        return $query->paginate(12);
    }

    public function activeList()
    {
        return $this->model::active()->with("media")->orderBy("sort")->orderBy("id", "desc")->get();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }
}
