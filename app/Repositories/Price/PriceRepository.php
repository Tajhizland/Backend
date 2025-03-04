<?php

namespace App\Repositories\Price;

use App\Models\Price;
use App\Repositories\Base\BaseRepository;

class PriceRepository extends BaseRepository implements PriceRepositoryInterface
{
    public function __construct(Price $model)
    {
        parent::__construct($model);
    }

    public function createPrice($productColorId, $price, $discount, $discountExpireTime=null)
    {
        $this->create(
            [
                "product_color_id" => $productColorId,
                "price" => $price,
                "discount_expire_time" => $discountExpireTime,
                "discount" => $discount
            ]
        );
    }

    public function updatePrice($productColorId, $price, $discount, $discountExpireTime=null)
    {
        $this->model::where("product_color_id", $productColorId)
            ->update(
                [
                    "price" => $price,
                    "discount_expire_time" => $discountExpireTime,
                    "discount" => $discount
                ]
            );
    }

    public function findByProductColorId($productColorId)
    {
        return $this->model::where("product_color_id", $productColorId)->first();
    }
}
