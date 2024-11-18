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
        return $this->model->active()->where("url", $url)->first();
    }


    public function findById($id)
    {
        return $this->model::find($id);
    }

    public function getByCategoryId($id)
    {
        return $this->model::whereHas("productCategories", function ($query) use ($id) {
            $query->where("category_id",$id);
        })->limit(10)->get();
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
                }), AllowedFilter::callback('brand', function ($query, $value) {
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
        return $this->model::where("name", "like", "%$query%")->limit(config("settings.search_item_limit"))->get();
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

    public function activeProductQuery($categoryId)
    {
        return $this->model::active()->hasColor()
            ->whereHas("productCategories", function ($query) use ($categoryId) {
                $query->where("category_id", $categoryId);
            })->orderBy("sort");
    }
  public function activeProductByBrandQuery($brandId)
    {
        return $this->model::active()->hasColor()
            ->where("brand_id", $brandId);
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
  public function categoryFilter($query, $categoryId)
    {
        return $query->whereHas("productCategories", function ($q) use ($categoryId) {
            $q->where("category_id",$categoryId);
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
       return $this->model::where("name", "like", "%$query%")->latest("id")->paginate($this->pageSize);
   }

    public function getAllByCategoryId($id)
    {
        return $this->model::whereHas("productCategories", function ($query) use ($id) {
        $query->where("category_id",$id);
    })->orderBy("sort")->get();
    }
    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }
    public function getDiscountedProducts()
    {
        return $this->model::active()->hasDiscount()->paginate($this->pageSize);
    }

    public function getSpecial()
    {
        return $this->model::active()->whereHas("special")->latest("id")->paginate($this->pageSize);
    }
}
