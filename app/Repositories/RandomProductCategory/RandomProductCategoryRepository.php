<?php

namespace App\Repositories\RandomProductCategory;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\RandomProductCategory;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RandomProductCategoryRepository extends BaseRepository implements RandomProductCategoryRepositoryInterface
{
    public function __construct(RandomProductCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(RandomProductCategory::class)
            ->with("category")
            ->allowedFilters(['id', 'category_id', 'created_at',
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('category', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),])
            ->allowedSorts(['id', 'category_id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function add($categoryId)
    {
        return $this->model::firstOrCreate([
            "category_id" => $categoryId,
        ]);
    }

    /**
     * چند محصول تصادفی از مجموعِ دسته‌بندی‌های انتخاب‌شده در پنل.
     *
     * انتخاب دو مرحله‌ای است تا ORDER BY RAND() روی کوئری سنگینِ کارت محصول اجرا نشود:
     * اول فقط شناسه‌ی کاندیدها خوانده می‌شود (بدون eager load) و در PHP نمونه‌برداری
     * می‌شود، بعد همان چند شناسه با اسکوپ forCard واکشی می‌شوند.
     *
     * چون پاسخ صفحه اصلی کش می‌شود، ترکیب خروجی هر settings.home_page.cache_ttl
     * ثانیه یک‌بار عوض می‌شود، نه در هر ریکوئست.
     *
     * @return Collection<int, Product>
     */
    public function getRandomProductCards(?int $limit = null)
    {
        $limit ??= (int)config("settings.home_page.random_product_limit", 10);

        if ($limit <= 0) {
            return new Collection();
        }

        $categoryIds = $this->model::query()->pluck("category_id");

        if ($categoryIds->isEmpty()) {
            return new Collection();
        }

        $candidateIds = Product::query()
            ->select("products.id")
            ->where("products.status", ProductStatus::Active->value)
            ->hasColorHasStock()
            ->whereHas("categories", fn($query) => $query->whereIn("categories.id", $categoryIds))
            ->pluck("id");

        if ($candidateIds->isEmpty()) {
            return new Collection();
        }

        $pickedIds = $candidateIds->shuffle()->take($limit)->values();

        $products = Product::query()
            ->forCard()
            ->whereIn("products.id", $pickedIds)
            ->get()
            ->keyBy("id");

        // whereIn خروجی را به ترتیب id برمی‌گرداند، پس ترتیب تصادفیِ مرحله‌ی قبل دوباره اعمال می‌شود.
        $ordered = $pickedIds
            ->map(fn($id) => $products->get($id))
            ->filter()
            ->values()
            ->all();

        return new Collection($ordered);
    }
}
