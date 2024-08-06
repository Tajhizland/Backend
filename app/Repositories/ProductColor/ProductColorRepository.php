<?php

namespace App\Repositories\ProductColor;

use App\Models\ProductColor;
use App\Repositories\Base\BaseRepository;

class ProductColorRepository extends BaseRepository implements ProductColorRepositoryInterface
{
    public function __construct(ProductColor $model)
    {
        parent::__construct($model);
    }
    public function createProductColor($name, $code, $productId, $status ,$deliveryDelay)
    {
        return $this->create(
            [
                "color_name"=>$name,
                "color_code"=>$code,
                "status"=>$status,
                "delivery_delay"=>$deliveryDelay,
                "product_id"=>$productId,
            ]
        );
    }
    public function updateProductColor($id,$name, $code, $status ,$deliveryDelay)
    {
        $this->model::find($id)
            ->update(
                [
                    "color_name"=>$name,
                    "color_code"=>$code,
                    "status"=>$status,
                    "delivery_delay"=>$deliveryDelay,
                ]
            );

    }
}
