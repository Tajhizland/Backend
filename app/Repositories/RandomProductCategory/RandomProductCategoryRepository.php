<?php

namespace App\Repositories\RandomProductCategory;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\RandomProductCategory;
use App\Enums\CategoryStatus;
use App\Models\Category;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RandomProductCategoryRepository extends BaseRepository implements RandomProductCategoryRepositoryInterface
{
    /** کلید کشِ فهرست شناسه‌ی کاندیدها (نه نتیجه‌ی انتخاب تصادفی) */
    private const CANDIDATE_CACHE_KEY = "shop:home_page:random_product_candidates:v1";

    public function __construct(RandomProductCategory $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(RandomProductCategory::class)
            ->with("category")
            ->allowedFilters(...['id', 'category_id', 'created_at',
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('category', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),])
            ->allowedSorts(...['id', 'category_id', 'created_at'])
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
     * اول شناسه‌ی همه‌ی کاندیدها گرفته می‌شود (کوئری سنگینِ whereHas، ولی فقط ستون id
     * و بدون eager load — پس کش می‌شود)، بعد در PHP نمونه‌برداری می‌شود و فقط همان
     * چند شناسه با اسکوپ forCard واکشی می‌شوند.
     *
     * شافل در هر فراخوانی انجام می‌شود، پس هر ریکوئست ترکیب متفاوتی می‌گیرد؛ چیزی
     * که کش می‌شود فهرست کاندیدهاست نه نتیجه‌ی انتخاب.
     *
     * @return Collection<int, Product>
     */
    public function getRandomProductCards(?int $limit = null)
    {
        $limit ??= (int)config("settings.home_page.random_product_limit", 10);

        if ($limit <= 0) {
            return new Collection();
        }

        $candidateIds = collect($this->getCandidateProductIds());

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

    /**
     * شناسه‌ی همه‌ی محصولات فعال و موجودِ دسته‌بندی‌های انتخاب‌شده.
     *
     * این کوئری سنگین است ولی خروجی‌اش فقط یک آرایه‌ی id است، پس کش می‌شود.
     * تازگی‌اش (ورود/خروج محصول از این فهرست) با candidate_cache_ttl کنترل می‌شود.
     *
     * @return array<int, int>
     */
    public function getCandidateProductIds(): array
    {
        $ttl = (int)config("settings.home_page.random_product_candidate_ttl", 600);

        if ($ttl <= 0) {
            return $this->queryCandidateProductIds();
        }

        return Cache::remember(self::CANDIDATE_CACHE_KEY, $ttl, fn() => $this->queryCandidateProductIds());
    }

    public function flushCandidateCache(): void
    {
        Cache::forget(self::CANDIDATE_CACHE_KEY);
    }

    /**
     * @return array<int, int>
     */
    private function queryCandidateProductIds(): array
    {
        $selectedIds = $this->model::query()->pluck("category_id")->all();

        if (empty($selectedIds)) {
            return [];
        }

        // انتخابِ یک دسته‌بندی پدر یعنی محصولات همه‌ی زیرشاخه‌هایش هم بیایند،
        // همان رفتاری که صفحه‌ی خودِ دسته‌بندی دارد.
        $categoryIds = $this->expandWithDescendantIds($selectedIds);

        return Product::query()
            ->select("products.id")
            ->where("products.status", ProductStatus::Active->value)
            ->hasColorHasStock()
            ->whereHas("categories", fn($query) => $query->whereIn("categories.id", $categoryIds))
            ->pluck("id")
            ->all();
    }

    /**
     * دسته‌بندی‌های داده‌شده به همراه همه‌ی زیرشاخه‌هایشان در هر عمقی.
     *
     * کل درختِ دسته‌بندی‌های فعال با یک کوئری خوانده می‌شود و پیمایش در PHP
     * انجام می‌گیرد؛ چون ورودی چند ریشه است و پیمایش بازگشتیِ رابطه‌ای برای هر
     * گره یک کوئری جدا می‌زد.
     *
     * @param array<int, int> $categoryIds
     * @return array<int, int>
     */
    private function expandWithDescendantIds(array $categoryIds): array
    {
        $roots = array_unique(array_map("intval", $categoryIds));

        if (empty($roots)) {
            return [];
        }

        $childrenByParent = Category::query()
            ->select("id", "parent_id")
            ->where("status", CategoryStatus::Active->value)
            ->whereNotNull("parent_id")
            ->get()
            ->groupBy("parent_id");

        $collected = [];
        $queue = array_values($roots);

        while (!empty($queue)) {
            $current = array_pop($queue);

            // اگر داده حلقه داشته باشد (دسته‌ای که جدِ خودش شده) اینجا متوقف می‌شود
            if (isset($collected[$current])) {
                continue;
            }
            $collected[$current] = true;

            foreach ($childrenByParent->get($current, []) as $child) {
                $queue[] = (int)$child->id;
            }
        }

        return array_keys($collected);
    }
}
