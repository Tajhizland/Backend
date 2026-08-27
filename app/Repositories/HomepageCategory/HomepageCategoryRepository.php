<?php

namespace App\Repositories\HomepageCategory;

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
    /**
     * دسته‌بندی‌های صفحه اصلی به همراه چند محصول موجودِ هر دسته.
     *
     * محصولات با اسکوپ forCard لود می‌شوند تا ستون‌ها و روابط سنگین
     * (review، comments، images کامل و …) وارد پاسخ صفحه اصلی نشوند.
     */
    public function getWithCategory(?int $productLimit = null)
    {
        $productLimit ??= (int)config("settings.home_page.category_product_limit", 8);

        return $this->model::query()
            ->select("id", "category_id", "icon")
            ->whereHas("category")
            ->with([
                'category:id,name,url,image,status,parent_id,type',
                'category.products' => fn ($query) => $query
                    ->forCard()
                    ->hasColorHasStock()
                    ->where("products.status", ProductStatus::Active->value)
                    ->limit($productLimit),
            ])
            ->latest("id")
            ->get();
    }
}
