<?php

namespace App\Repositories\Price;

use App\Models\Price;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PriceRepository extends BaseRepository implements PriceRepositoryInterface
{
    public function __construct(Price $model)
    {
        parent::__construct($model);
    }

    public function createPrice($productColorId, $price, $discount)
    {
        $this->create(
            [
                "product_color_id" => $productColorId,
                "price" => $price,
                "discount" => $discount
            ]
        );
    }

    public function updatePrice($productColorId, $price, $discount)
    {
        $this->model::where("product_color_id", $productColorId)
            ->update(
                [
                    "price" => $price,
                    "discount" => $discount
                ]
            );
    }
}
