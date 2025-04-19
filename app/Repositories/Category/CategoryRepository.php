<?php

namespace App\Repositories\Category;

use App\Enums\FilterStatus;
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
        return $this->model::with(["filters" => function ($query) {
            $query->with(["items" => function ($query) {
                $query->where("status", FilterStatus::Active->value);
            }])->where("status", FilterStatus::Active->value);
        }])->where("url", $url)->active()->first();
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

    public function list()
    {
        return $this->model::active()->select("name", "id")->get();
    }

    public function getByBrandId($brandId)
    {
        return $this->model::active()->whereHas('products', function ($query) use ($brandId) {
            $query->active()->hasColor()->where("brand_id", $brandId);
        })->get();
    }

    public function getSitemapData()
    {
        return $this->model::active()->select("url")->latest("id")->get();
    }

    public function getDiscountedCategory()
    {
        return $this->model::active()->whereHas('products', function ($query) {
            $query->active()->hasColor()->whereHas('products', function ($query) {
                $query->active()->hasColor()->hasDiscount();
            });
        })->get();
    }
}
