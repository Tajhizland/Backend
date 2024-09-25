<?php

namespace App\Repositories\PopularCategory;

use App\Models\PopularCategory;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PopularCategoryRepository extends BaseRepository implements PopularCategoryRepositoryInterface
{
    public function __construct(PopularCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(PopularCategory::class)
            ->with("category")
            ->allowedFilters(['id', 'created_at',
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('category', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),])
            ->paginate($this->pageSize);
    }

    public function add($categoryId)
    {
        return $this->model::create([
            "category_id" => $categoryId
        ]);
    }
    public function getWithCategory()
    {
        return $this->model::with("category")->get();
    }
}
