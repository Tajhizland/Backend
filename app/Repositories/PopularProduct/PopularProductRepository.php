<?php

namespace App\Repositories\PopularProduct;

use App\Models\PopularProduct;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PopularProductRepository extends BaseRepository implements PopularProductRepositoryInterface
{
    public function __construct(PopularProduct $model)
    {
        parent::__construct($model);
    }

    public function add($productId)
    {
        return $this->model::create([
            "product_id" => $productId
        ]);
    }

    public function dataTable()
    {
        return QueryBuilder::for(PopularProduct::class)
            ->with("product")
            ->allowedFilters(['id', 'created_at',
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('product', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),])
            ->paginate($this->pageSize);
    }
}
