<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function search($query)
    {
        return $this->model::where("name", "like", "%$query%")->active()->limit(config("settings.search_item_limit"))->get();
    }

    public function findByUrl($url)
    {
        return $this->model::where("url", $url)->active()->first();
    }

    public function createCategory($name, $status, $url, $image, $description, $parentId)
    {
        return $this->create(
            [
                "name" => $name,
                "status" => $status,
                "url" => $url,
                "image" => $image,
                "description" => $description,
                "parent_id" => $parentId
            ]
        );
    }

    public function updateCategory(Category $category, $name, $status, $url, $image, $description, $parentId)
    {
        $category->update([
            "name" => $name,
            "status" => $status,
            "url" => $url,
            "image" => $image,
            "description" => $description,
            "parent_id" => $parentId
        ]);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Category::class)
            ->select("categories.*")
            ->allowedFilters(['name', 'url', 'status', 'id', 'created_at', 'parent_id'])
            ->allowedSorts(['name', 'url', 'status', 'id', 'created_at', 'parent_id'])
            ->paginate($this->pageSize);
    }
}
