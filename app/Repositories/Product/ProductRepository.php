<?php

namespace App\Repositories\Product;

use App\Enums\ProductColorStatus;
use App\Models\Dictionary;
use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Product\SortProductByCategoryName;
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
        return $product->increment('view');
    }

    public function dataTable()
    {
        return QueryBuilder::for(Product::class)
            ->select("products.*")
            ->where("is_stock", 0)
            ->withCount("images") // اضافه کردن تعداد عکس‌ها
            ->allowedFilters([
                'name', 'url', 'status', 'id', 'view', 'created_at',
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
            ->allowedSorts([
                'id', 'name', 'url', 'status', 'view', 'created_at',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
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
            ->allowedFilters([
                'name', 'url', 'status', 'id', 'view', 'created_at',
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
            ->allowedSorts([
                'id', 'name', 'url', 'status', 'view', 'created_at',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
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

    public function getStockProducts()
    {
        return $this->model::withActiveColor()->active()->isStock();
    }

    public function getStockProductIds()
    {
        return $this->model::withActiveColor()->active()->isStock()->pluck("id");;
    }

    public function getSpecial()
    {
        return $this->model::withActiveColor()->active()->whereHas("special")->latest("id")->paginate($this->pageSize);
    }

    public function getSitemapData()
    {
        return $this->model::active()->select("url")->latest("id")->get();
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
            ->allowedFilters(['name', 'url', 'status', 'id', 'view', 'created_at'
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
            ->allowedSorts(['id', 'name', 'url', 'status', 'view', 'created_at',
                AllowedSort::custom("category", new SortProductByCategoryName()),
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
            ->allowedFilters([
                'name', 'url', 'status', 'id', 'view', 'created_at',
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
            ->allowedSorts([
                'id', 'name', 'url', 'status', 'view', 'created_at',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
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
            ->allowedFilters([
                'name', 'url', 'status', 'id', 'view', 'created_at',
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
            ->allowedSorts([
                'id', 'name', 'url', 'status', 'view', 'created_at',
                'images_count', // سورت روی تعداد عکس‌ها
                AllowedSort::custom("category", new SortProductByCategoryName()),
            ])
            ->whereHas("activeProductColors")
            ->whereHas("prices", function ($query) {
                $query->where("discount", ">", 0)
                    ->where(function ($q) {
                        $q->whereNull('discount_expire_time')
                            ->orWhere('discount_expire_time', '>', now());
                    });
            })
            ->paginate($this->pageSize);
    }

    public function searchList($categoryId, $brandId, $discountId=0)
    {
        return $this->model::query()
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

}
