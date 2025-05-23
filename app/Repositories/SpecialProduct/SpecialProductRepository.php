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
            ->allowedFilters(['id', 'product_id', 'homepage', 'created_at',
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('product', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),])
            ->allowedSorts(['product_id', 'homepage', 'id', 'created_at'])
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
        return $this->model::where("homepage", 1)->with(["product" => function ($query) {
            $query->WithActiveColor();
        }])->orderBy("sort")->get();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("product_id", $id)->update(["sort" => $sort]);
    }
}
