<?php

namespace App\Repositories\SpecialProduct;

use App\Models\SpecialProduct;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SpecialProductRepository extends BaseRepository implements SpecialProductRepositoryInterface
{
    public function __construct(SpecialProduct $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(SpecialProduct::class)
            ->with("product")
            ->allowedFilters(['id','homepage', 'created_at',
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('product', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),])
            ->paginate($this->pageSize);
    }

    public function add($productId)
    {
        return $this->model::create([
            "product_id" => $productId
        ]);
    }

    public function getWithProduct()
    {
        return $this->model::where("homepage", 1)->with("product")->get();
    }
}
