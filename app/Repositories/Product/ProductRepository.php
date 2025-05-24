<?php

namespace App\Repositories\Product;

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
            ->with(["groupItems","groupItems.product","groupItems.value"])
            ->whereHas("activeProductColors")
            ->active()
            ->where("url", $url)
            ->first();
    }


    public function findById($id)
    {
        return $this->model::find($id);
    }

    public function getByCategoryId($id, $except)
    {
        return $this->model::active()->HasColorHasStock()->whereHas("productCategories", function ($query) use ($id) {
            $query->where("category_id", $id);
        })->where("id", "<>", $except)->limit(10)->get();
    }

    public function incrementViewCount($product)
    {
        return $product->increment('view');
    }

    public function dataTable()
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
            ->paginate($this->pageSize);
    }

    public function search($query)
    {
        $keywords = explode(' ', $query);

        return $this->model::where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->where('name', 'like', '%' . $word . '%');
            }
        })->whereHas("activeProductColors")
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
            ->withActiveColor()
            ->whereHas("productCategories", function ($query) use ($categoryIds) {
                $query->whereIn("category_id", $categoryIds);
            })
            ->customOrder();
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
        return $this->model::where("name", "like", "%$query%")
            ->customOrder()->paginate($this->pageSize);
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
            ->where("type","group")
            ->paginate($this->pageSize);
    }
}
