<?php

namespace App\Repositories\ProductOption;

use App\Models\ProductOption;
use App\Repositories\Base\BaseRepository;

class ProductOptionRepository extends BaseRepository implements ProductOptionRepositoryInterface
{
    public function __construct(ProductOption $model)
    {
        parent::__construct($model);
    }

    public function findProductOption($productId, $optionItemId)
    {
        return $this->model::where("product_id",$productId)->where("option_item_id",$optionItemId)->first();
    }

    public function store($productId, $optionItemId, $value)
    {
        return $this->create([
            "product_id"=>$productId,
            "option_item_id"=>$optionItemId,
            "value"=>$value
        ]);
    }

    public function updateValue(ProductOption $productFilter, $value)
    {
       return $productFilter->update(["value" => $value]);
    }
    public function deleteValue(ProductOption $productFilter)
    {
       return $productFilter->delete();
    }
    
}
