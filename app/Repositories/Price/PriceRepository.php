<?php

namespace App\Repositories\Price;

use App\Models\Price;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;

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
                "discount_expire_time" => $this->normalizeExpireTime($discountExpireTime),
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
                    "discount_expire_time" => $this->normalizeExpireTime($discountExpireTime),
                    "discount" => $discount
                ]
            );
    }

    /** فرم‌های ادمین برای «بدون تاریخ» رشته‌ی خالی می‌فرستند که ستون timestamp قبولش نمی‌کند. */
    private function normalizeExpireTime($discountExpireTime)
    {
        return $discountExpireTime === "" ? null : $discountExpireTime;
    }

    public function findByProductColorId($productColorId)
    {
        return $this->model::where("product_color_id", $productColorId)->first();
    }

    public function findFirstExpireDiscount()
    {
        return $this->model::whereNotNull("discount_expire_time")->where("discount_expire_time",">",Carbon::now())->where("discount",">",0)->first();
    }
}
