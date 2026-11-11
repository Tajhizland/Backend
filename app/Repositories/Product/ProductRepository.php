<?php

namespace App\Repositories\Product;

use App\Enums\ProductColorStatus;
use App\Models\Dictionary;
use App\Models\DiscountItem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Product\SortProductByBrandName;
use App\Services\Sort\Product\SortProductByCategoryName;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;


class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findByUrl($url)
    {
        return $this->model::withActiveColor()
            ->whereHas("activeProductColors")
            ->active()
            ->isProduct()
            ->where("url", $url)
            ->with([
                "stockOf" => function ($query) {
                    $query->withActiveColor();
                },
                'productOptions' => function ($query) {
                    $query->join('option_items', 'product_options.option_item_id', '=', 'option_items.id')
                        ->orderBy('option_items.sort')
                        ->select('product_options.*');
                },
        "guaranties" => function ($query) {
                    $query->orderBy('free', "desc");
                }
            ])
            ->first();
    }

    public function findGroupByUrl($url)
    {
        return $this->model::with(["groupItems", "groupItems.product", "groupItems.product.images", "groupItems.value", "groupItems.value.groupField"])
            ->active()
            ->isGroup()
            ->where("url", $url)
            ->first();
    }


    public function findById($id)
    {
        return $this->model::with("stockOf")->find($id);
    }

    public function getByCategoryId($id, $except, $limit = 10)
    {
        return $this->model::active()->HasColorHasStock()->whereHas("productCategories", function ($query) use ($id) {
            $query->where("category_id", $id);
        })->where("id", "<>", $except)->limit($limit)->get();
    }

    /**
     * محصولاتی که این ولاگ به آن‌ها لینک شده است (جدول product_videos).
     *
     * همان فیلتر محصولات مرتبطِ صفحه محصول: فقط محصول فعال و موجود،
     * و با forCard تا کارت‌های اسلایدر بدون N+1 رندر شوند.
     */
    public function getByVlogId($vlogId, $limit = 10)
    {
        return $this->model::active()
            ->HasColorHasStock()
            ->forCard()
            ->whereHas("videos", function ($query) use ($vlogId) {
                $query->where("vlog_id", $vlogId);
            })
            ->latest("products.id")
            ->limit($limit)
            ->get();
    }

    public function getByCategoryIds(array $categoryIds, $except, $limit = 10)
    {
        $results = collect(); // برای ذخیره نتایج
        $remainingLimit = $limit; // تعداد محصولات باقی‌مانده برای رسیدن به limit
        $perCategoryLimit = ceil($limit / count($categoryIds)); // حد اولیه برای هر دسته‌بندی

        // مرحله 1: گرفتن محصولات از هر دسته‌بندی
        foreach ($categoryIds as $categoryId) {
            if ($remainingLimit <= 0) {
                break; // اگر به limit رسیدیم، حلقه را متوقف می‌کنیم
            }

            $products = $this->model::active()
                ->HasColorHasStock()
                ->withActiveColor()
                ->whereHas("productCategories", function ($query) use ($categoryId) {
                    $query->where("category_id", $categoryId);
                })
                ->where("id", "<>", $except)
                ->limit($perCategoryLimit)
                ->get();

            $results = $results->merge($products);
            $remainingLimit -= $products->count();
        }

        // مرحله 2: پر کردن محصولات باقی‌مانده از دسته‌بندی‌های دارای محصول
        if ($remainingLimit > 0 && !$results->isEmpty()) {
            $availableCategoryIds = $categoryIds; // دسته‌بندی‌های موجود
            foreach ($availableCategoryIds as $categoryId) {
                if ($remainingLimit <= 0) {
                    break;
                }

                // گرفتن محصولات اضافی از دسته‌بندی، بدون محصولاتی که قبلاً انتخاب شده‌اند
                $existingProductIds = $results->pluck('id')->toArray();
                $additionalProducts = $this->model::active()
                    ->HasColorHasStock()
                    ->withActiveColor()
                    ->whereHas("productCategories", function ($query) use ($categoryId) {
                        $query->where("category_id", $categoryId);
                    })
                    ->where("id", "<>", $except)
                    ->whereNotIn("id", $existingProductIds) // جلوگیری از انتخاب محصولات تکراری
                    ->limit($remainingLimit)
                    ->get();

                $results = $results->merge($additionalProducts);
                $remainingLimit -= $additionalProducts->count();
            }
        }

        // محدود کردن نتایج نهایی به $limit
        return $results->take($limit);
    }

    public function incrementViewCount($product)
    {
        return Product::withoutTimestamps(fn () => $product->increment('view'));
    }

    public function dataTable()
    {
        return QueryBuilder::for(Product::class)
            ->select("products.*")
            ->where("is_stock", 0)
            ->withCount("images") // اضافه کردن تعداد عکس‌ها
            ->allowedFilters(...[
                'width', 'height', 'length', 'weight', 'name', 'url', 'status', 'id', 'view', 'created_at', 'is_stock',
                'images_count', // فیلتر روی تعداد عکس‌ها
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('brand_name', function ($query, $value) {
                    $query->whereHas('brand', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(...[
                'width', 'height', 'length', 'weight', 'id', 'name', 'url', 'status', 'view', 'created_at', 'is_stock',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
                AllowedSort::custom("brand_name", new SortProductByBrandName()),
            ])
            ->latest("id")
            ->paginate($this->pageSize);
    }

    public function stockDataTable()
    {
        return QueryBuilder::for(Product::class)
            ->where("is_stock", 1)
            ->select("products.*")
            ->withCount("images") // اضافه کردن تعداد عکس‌ها
            ->allowedFilters(...[
                'name', 'url', 'status', 'id', 'view', 'created_at', 'is_stock', 'testing_time',
                'images_count', // فیلتر روی تعداد عکس‌ها
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('brand_name', function ($query, $value) {
                    $query->whereHas('brand', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(...[
                'id', 'name', 'url', 'status', 'view', 'created_at', 'is_stock', 'testing_time',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
                AllowedSort::custom("brand_name", new SortProductByBrandName()),
            ])
            ->latest("id")
            ->paginate($this->pageSize);
    }

    public function search($query)
    {
        $keywords = explode(' ', $query);
        $dictionary = Dictionary::whereIn('original_word', $keywords)->get()
            ->pluck('mean', 'original_word')
            ->toArray();

        return $this->model::where(function ($q) use ($keywords, $dictionary) {
            foreach ($keywords as $word) {
                $q->where(function ($sub) use ($word, $dictionary) {
                    // همیشه خود کلمه سرچ میشه
                    $sub->where('name', 'like', '%' . $word . '%');

                    // اگه تو دیکشنری باشه mean هم اضافه می‌کنیم
                    if (isset($dictionary[$word])) {
                        $sub->orWhere('name', 'like', '%' . $dictionary[$word] . '%');
                    }
                });
            }
        })
            ->whereHas("activeProductColors")
            ->limit(config("settings.search_item_limit"))
            ->get();
    }

    public function searchProductWithCategory($query, $categoryId)
    {
        return $this->model::active()->hasColor()->where("name", "like", "%$query%")
            ->whereHas("productCategories", function ($q) use ($categoryId) {
                $q->where("id", $categoryId);
            })->paginate($this->pageSize);
    }

    public function showFavoriteList($userId)
    {
        return $this->model::whereHas("favorites", function ($query) use ($userId) {
            $query->where("user_id", $userId);
        })->paginate($this->pageSize);
    }

    public function activeProductQuery($categoryIds)
    {
        return $this->model::active()->hasColor()
            ->isProduct()
            ->withActiveColor()
            ->whereHas("productCategories", function ($query) use ($categoryIds) {
                $query->whereIn("category_id", $categoryIds);
            })
            ->customOrder();
    }

    public function activeGroupLimit($categoryIds)
    {
        return $this->model::with(["groupItems", "groupItems.product"])
            ->active()
            ->hasColor()
            ->withActiveColor()
            ->isGroup()
            ->whereHas("productCategories", function ($query) use ($categoryIds) {
                $query->whereIn("category_id", $categoryIds);
            })
            ->customOrder()
            ->limit(10)
            ->get();
    }

    public function activeGroupPaginate($categoryIds)
    {
        return $this->model::with(["groupItems", "groupItems.product"])
            ->active()
            ->hasColor()
            ->withActiveColor()
            ->isGroup()
            ->whereHas("productCategories", function ($query) use ($categoryIds) {
                $query->whereIn("category_id", $categoryIds);
            })
            ->customOrder()
            ->paginate($this->pageSize);
    }

    public function activeProductByBrandQuery($brandId)
    {
        return $this->model::active()->hasColor()
            ->where("brand_id", $brandId)
            ->withActiveColor()
            ->customOrder();
    }


    public function otherFilter($key, $values, $query)
    {
        return $query->whereHas("productFilters", function ($q) use ($key, $values) {
            $q->where("filter_id", $key)->whereIn("filter_item_id", $values);
        });
    }

    public function paginated($query)
    {
        return $query->paginate($this->pageSize);
    }

    /** Price bounds cover the complete category, before filters or pagination. */
    public function priceBounds($query): array
    {
        $bounds = DB::query()->fromSub($this->effectiveColorPrices(), 'offers')
            ->whereIn('product_id', (clone $query)->setEagerLoads([])->reorder()->select('products.id'))
            ->selectRaw('MIN(sale_price) as minimum, MAX(sale_price) as maximum')->first();

        return ['minPrice' => (int) ($bounds->minimum ?? 0), 'maxPrice' => (int) ($bounds->maximum ?? 0)];
    }

    public function priceRangeFilter($query, $minimum, $maximum)
    {
        $offers = DB::query()->fromSub($this->effectiveColorPrices(), 'offers')->select('product_id');
        if (is_numeric($minimum) && $minimum >= 0) {
            $offers->where('sale_price', '>=', $minimum);
        }
        if (is_numeric($maximum) && $maximum >= 0) {
            $offers->where('sale_price', '<=', $maximum);
        }

        // Both limits must match the same color, including its current discount.
        return $query->whereIn('products.id', $offers);
    }

    private function effectiveColorPrices()
    {
        $now = now();
        $discount = DiscountItem::query()->select('discount_price')
            ->whereColumn('product_color_id', 'product_colors.id')
            ->where(fn ($query) => $query->whereNull('discount_expire_time')->orWhere('discount_expire_time', '>', $now))
            ->whereHas('discount', function ($query) use ($now) {
                $query->where('status', 1)
                    ->where(fn ($query) => $query->whereNull('start_date')->orWhere('start_date', '<', $now))
                    ->where(fn ($query) => $query->whereNull('end_date')->orWhere('end_date', '>', $now));
            })->orderByDesc('discount_id')->limit(1);
        $colors = ProductColor::query()
            ->whereIn('product_colors.status', [ProductColorStatus::Active->value, ProductColorStatus::Limit->value])
            ->join('prices', 'prices.product_color_id', '=', 'product_colors.id')->where('prices.price', '>', 0)
            ->select('product_colors.product_id', 'prices.price as regular_price')
            ->selectSub($discount, 'discounted_price');

        return DB::query()->fromSub($colors, 'color_prices')->select('product_id')
            ->selectRaw('CASE WHEN discounted_price > 0 AND discounted_price < regular_price THEN discounted_price ELSE regular_price END as sale_price');
    }

    public function minPriceFilter($query, $minPrice)
    {
        return $query->whereHas("prices", function ($q) use ($minPrice) {
            $q->where("price", ">=", $minPrice);
        });
    }

    public function maxPriceFilter($query, $maxPrice)
    {
        return $query->whereHas("prices", function ($q) use ($maxPrice) {
            $q->where("price", "<=", $maxPrice);
        });
    }

    public function categoryFilters($query, $categoryId)
    {
        return $query->whereHas("productCategories", function ($q) use ($categoryId) {
            $q->whereIn("category_id", $categoryId);
        });
    }

    public function categoryFilter($query, $categoryId)
    {
        return $query->whereHas("productCategories", function ($q) use ($categoryId) {
            $q->where("category_id", $categoryId);
        });
    }

    public function nameFilter($query, $name)
    {
        return $query->where("name", "like", "%$name%");

    }

    public function hasStockFilter($query)
    {
        return $query->whereHas("stocks", function ($q) {
            $q->where("stock", ">", 0);
        });
    }

    public function createProduct($name, $url, $description, $study, $status, $brandId, $metaTitle, $metaDescription)
    {
        return $this->create([
            "name" => $name,
            "url" => $url,
            "description" => $description,
            "study" => $study,
            "status" => intval($status),
            "view" => 0,
            "brand_id" => $brandId,
            "meta_title" => $metaTitle,
            "meta_description" => $metaDescription,
        ]);
    }

    public function updateProduct($id, $name, $url, $description, $study, $status, $brandId, $metaTitle, $metaDescription)
    {
        return $this->model::find($id)->update([
            "name" => $name,
            "url" => $url,
            "description" => $description,
            "study" => $study,
            "status" => intval($status),
            "brand_id" => $brandId,
            "meta_title" => $metaTitle,
            "meta_description" => $metaDescription,
        ]);
    }

    public function getNewProduct()
    {
        return $this->model::latest("id")->limit(config("settings.home_page_item_limit"))->get();
    }

    public function getHasDiscountProduct()
    {
        return $this->model::hasDiscount()->limit(config("settings.home_page_item_limit"))->get();
    }

    public function getMostPopularProduct()
    {
        return $this->model::mostPopular()->limit(config("settings.home_page_item_limit"))->get();
    }

    public function getCustomCategoryProduct($categoryId)
    {
        return $this->model::whereHas("productCategories", function ($query) use ($categoryId) {
            $query->where("category_id", $categoryId);
        })->limit(config("settings.home_page_item_limit"))->get();
    }

    public function searchPaginate($query)
    {
        $keywords = explode(' ', $query);

// گرفتن همه‌ی لغات موجود در دیکشنری
        $dictionary = Dictionary::whereIn('original_word', $keywords)->get()
            ->groupBy('original_word'); // ممکنه یک کلمه چند mean داشته باشه

        return $this->model::where(function ($q) use ($keywords, $dictionary) {
            foreach ($keywords as $word) {
                $q->where(function ($sub) use ($word, $dictionary) {
                    // همیشه خود کلمه
                    $sub->where('name', 'like', '%' . $word . '%');

                    // اگر معادل در دیکشنری هست، اضافه کن
                    if ($dictionary->has($word)) {
                        foreach ($dictionary[$word] as $dict) {
                            $sub->orWhere('name', 'like', '%' . $dict->mean . '%');
                        }
                    }
                });
            }
        })
            ->whereHas("activeProductColors")
            ->withActiveColor()
            ->customOrder()
            ->paginate($this->pageSize);

    }

    public function getAllByCategoryId($id)
    {
        return $this->model::whereHas("productCategories", function ($query) use ($id) {
            $query->where("category_id", $id);
        })->orderBy("sort")->get();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function getDiscountedProducts()
    {
        return $this->model::withActiveColor()->active()->hasDiscount();
    }

    public function getTopDiscountedProducts()
    {
        return $this->topDiscountedQuery()->withActiveColor()->get();
    }

    /** بیشترین درصد تخفیف معتبر در کل کاتالوگِ فعال و موجود، مستقل از محدودیت اسلایدر. */
    public function maxActiveDiscountPercent(): int
    {
        $now = now();
        // Select the same latest active discount used by the product and checkout pages.
        $discount = DiscountItem::query()->select('discount_price')
            ->whereColumn('product_color_id', 'product_colors.id')
            ->where(fn ($query) => $query->whereNull('discount_expire_time')->orWhere('discount_expire_time', '>', $now))
            ->whereHas('discount', function ($query) use ($now) {
                $query->where('status', 1)
                    ->where(fn ($query) => $query->whereNull('start_date')->orWhere('start_date', '<', $now))
                    ->where(fn ($query) => $query->whereNull('end_date')->orWhere('end_date', '>', $now));
            })->orderByDesc('discount_id')->limit(1);

        $offers = ProductColor::query()
            ->whereIn('product_colors.status', [ProductColorStatus::Active->value, ProductColorStatus::Limit->value])
            ->whereHas('product', fn ($query) => $query->active()->isProduct())
            ->whereHas('stock', fn ($query) => $query->where('stock', '>', 0))
            ->join('prices', 'prices.product_color_id', '=', 'product_colors.id')
            ->where('prices.price', '>', 0)
            ->select('prices.price as regular_price')->selectSub($discount, 'sale_price');

        $maximum = DB::query()->fromSub($offers, 'offers')
            ->where('sale_price', '>', 0)->whereColumn('sale_price', '<', 'regular_price')
            ->selectRaw('MAX((regular_price - sale_price) * 100.0 / regular_price) as maximum')
            ->value('maximum');

        // Do not advertise a larger percentage than any customer can actually receive.
        return (int) floor((float) $maximum);
    }

    /**
     * همان محصولات پرتخفیف، ولی فقط با ستون‌ها و روابط لازم برای «کارت محصول».
     *
     * برای صفحه اصلی استفاده می‌شود تا ProductResource سنگین (با review/comments/…)
     * روی ده‌ها محصول اجرا نشود.
     */
    public function getTopDiscountedProductCards(?int $limit = null)
    {
        $query = $this->topDiscountedQuery()->forCard();

        if ($limit !== null && $limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * کوئری پایه‌ی محصولات دارای تخفیف top، مرتب‌شده بر اساس sort آیتم تخفیف.
     */
    private function topDiscountedQuery()
    {
        return $this->model::active()->hasTopDiscount()
            ->orderBy(
                DiscountItem::select("discount_items.sort")
                    ->join("product_colors", "product_colors.id", "=", "discount_items.product_color_id")
                    ->whereColumn("product_colors.product_id", "products.id")
                    ->where("discount_items.top", 1)
                    ->orderBy("discount_items.sort")
                    ->limit(1)
            );
    }

    public function getStockProducts()
    {
        return $this->model::withActiveColor()->active()->isStock()->customOrder();
    }

    /**
     * آیا حداقل یک محصول کارکرده‌ی فعال با رنگِ فعال، موجود و قیمت‌دار وجود دارد؟
     *
     * صفحه اصلی با این فلگ تصمیم می‌گیرد لینک «کارکرده» را نشان بدهد یا نه.
     */
    public function hasAvailableStockProducts(): bool
    {
        return $this->model::active()
            ->isStock()
            ->whereHas("productColors", function ($query) {
                $query->whereIn("status", [ProductColorStatus::Active->value, ProductColorStatus::Limit->value])
                    ->whereHas("stock", fn($subQuery) => $subQuery->where("stock", ">", 0))
                    ->whereHas("price", fn($subQuery) => $subQuery->where("price", ">", 0));
            })
            ->exists();
    }

    public function getStockProductIds()
    {
        return $this->model::withActiveColor()->active()->isStock()->pluck("id");;
    }

    public function getSpecial()
    {
        return $this->model::withActiveColor()->active()->whereHas("special")
            ->withExists(['activeProductColors as available_for_purchase' => fn ($query) => $query
                ->whereHas('stock', fn ($query) => $query->where('stock', '>', 0))
                ->whereHas('price', fn ($query) => $query->where('price', '>', 0))])
            ->orderByDesc('available_for_purchase')->latest("id")->paginate($this->pageSize);
    }

    public function getSitemapData()
    {
        return $this->model::active()->isProduct()->whereHas("activeProductColors")
            ->select("id", "url", "updated_at")
            ->withMax(["prices", "stocks", "productColors", "images", "confirmedComments", "productOptions", "discountItems"], "updated_at")
            ->latest("id")->get();
    }

    public function customPaginate($perPage)
    {
        return $this->model::active()->latest("id")->paginate($perPage);
    }

    public function getTorobProducts()
    {
        return $this->model::active()->latest("id")->paginate(100);
    }

    public function getDiscountedProductsId()
    {
        return $this->model::active()->hasDiscount()->pluck("id");
    }

    public function groupDataTable()
    {
        return QueryBuilder::for(Product::class)
            ->select("products.*")
            ->allowedFilters(...['name', 'url', 'status', 'id', 'view', 'created_at'
                , AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }), AllowedFilter::callback('brand_name', function ($query, $value) {
                    $query->whereHas('brand', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(...['id', 'name', 'url', 'status', 'view', 'created_at',
                AllowedSort::custom("category", new SortProductByCategoryName()),
                AllowedSort::custom("brand_name", new SortProductByBrandName()),
            ])
            ->where("type", "group")
            ->paginate($this->pageSize);
    }

    public function findProductWithOption($id)
    {
        return $this->model::active()->with(["productOptions", "images"])->find($id);
    }

    public function searchWithOption($query, $categoryIds)
    {
        $keywords = explode(' ', $query);

        return $this->model::where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->where('name', 'like', '%' . $word . '%');
            }
        })->whereHas("categories", function ($subQuery) use ($categoryIds) {
            $subQuery->whereIn("category_id", $categoryIds);
        })
            ->with(["productOptions", "images"])
            ->whereHas("activeProductColors")
            ->limit(config("settings.search_item_limit"))
            ->get();
    }

    public function getWithOption($categoryIds)
    {
        return $this->model::whereHas("categories", function ($subQuery) use ($categoryIds) {
            $subQuery->whereIn("category_id", $categoryIds);
        })
            ->with(["productOptions", "images"])
            ->whereHas("activeProductColors")
            ->get();
    }

    public function hasLimitDataTable()
    {
        return QueryBuilder::for(Product::class)
            ->select("products.*")
            ->withCount("images") // اضافه کردن تعداد عکس‌ها
            ->allowedFilters(...[
                'name', 'url', 'status', 'id', 'view', 'created_at', 'is_stock',
                'width', 'height', 'length', 'weight',
                'images_count', // فیلتر روی تعداد عکس‌ها
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('brand_name', function ($query, $value) {
                    $query->whereHas('brand', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(...[
                'id', 'name', 'url', 'status', 'view', 'created_at', 'is_stock',
                'width', 'height', 'length', 'weight',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
                AllowedSort::custom("brand_name", new SortProductByBrandName()),
            ])
            ->whereHas("productColors", function ($query) {
                $query->where("status", ProductColorStatus::Limit->value);
            })
            ->paginate($this->pageSize);
    }

    public function hasDiscountDataTable()
    {
        return QueryBuilder::for(Product::class)
            ->select("products.*")
            ->withCount("images") // اضافه کردن تعداد عکس‌ها
            ->allowedFilters(...[
                'name', 'url', 'status', 'id', 'view', 'created_at', 'is_stock',
                'width', 'height', 'length', 'weight',
                'images_count', // فیلتر روی تعداد عکس‌ها
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('brand_name', function ($query, $value) {
                    $query->whereHas('brand', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(...[
                'id', 'name', 'url', 'status', 'view', 'created_at', 'is_stock',
                'width', 'height', 'length', 'weight',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
                AllowedSort::custom("brand_name", new SortProductByBrandName()),
            ])
            ->whereHas("activeProductColors")
            ->whereHas("prices")
            ->paginate($this->pageSize);
    }

    public function searchList($categoryId, $brandId, $searchQuery, $discountId = 0)
    {
        return $this->model::query()
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('productCategories', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->when($brandId, function ($q) use ($brandId) {
                $q->where('brand_id', $brandId);
            })
            ->with(["activeProductColors", "activeProductColors.discountItem" => function ($query) use ($discountId) {
                $query->where('discount_id', $discountId);
            }])
            ->get();
    }
    public function getTorobProductsWithIds($page_uniques)
    {
        return $this->model::active()->latest("id")->whereIn("id",$page_uniques)->paginate(100);

    }
    public function getTorobProductsWithUrls($page_urls)
    {
        return $this->model::active()->latest("id")->whereIn("url",$page_urls)->paginate(100);
    }

}
