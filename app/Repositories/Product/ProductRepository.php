<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\ProductByCategorySort;
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
        return $this->get([["url", $url]], 1);
    }

    public function incrementViewCount($product)
    {
        return $product->increment('view');
    }

    public function getPaginated()
    {
        return QueryBuilder::for(Product::class)
            ->select("products.*")
            ->allowedFilters(['name', 'url', 'status', 'id', 'created_at'
                , AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('categories', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(['id', 'name', 'url', 'status', 'created_at',
                AllowedSort::custom("category", new ProductByCategorySort()),
            ])
            ->paginate();
    }
    public function search($query)
    {
        return $this->model::where("name","like","%$query%")->limit(config("settings.search_item_limit"))->get();
    }

}
