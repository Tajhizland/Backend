<?php

namespace App\Repositories\HomepageCategory;

use App\Enums\ProductColorStatus;
use App\Enums\ProductStatus;
use App\Models\HomepageCategory;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class HomepageCategoryRepository extends BaseRepository implements HomepageCategoryRepositoryInterface
{
    public function __construct(HomepageCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(HomepageCategory::class)
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
        return $this->model::with([
            'category',
            'category.products' => function ($query) {
                $query->whereHas("productColors", function ($query) {
                    $query->where("status", "<>", ProductColorStatus::DeActive->value)
                        ->whereHas("stock", function ($subQuery) {
                            $subQuery->where("stock", ">", 0);
                        });
                })->where("status", ProductStatus::Active->value)->limit(12);
            }
        ])->latest("id")->get();
    }
}
