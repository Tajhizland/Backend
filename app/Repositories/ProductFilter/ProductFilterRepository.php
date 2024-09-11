<?php

namespace App\Repositories\ProductFilter;

use App\Models\ProductFilter;
use App\Repositories\Base\BaseRepository;

class ProductFilterRepository extends BaseRepository implements ProductFilterRepositoryInterface
{
    public function __construct(ProductFilter $model)
    {
        parent::__construct($model);
    }

    public function findProductFilter($productId, $filterId)
    {
        return $this->model::where("product_id", $productId)->where("filter_id", $filterId)->first();
    }

    public function store($productId, $filterId, $filterItemId)
    {
        return $this->create([
            "product_id" => $productId,
            "filter_id" => $filterId,
            "filter_item_id" => $filterItemId,
        ]);
    }

    public function updateFilterItem(ProductFilter $productFilter, $filterItemId)
    {
        return $productFilter->update(["filter_item_id" => $filterItemId]);
    }
}
