<?php

namespace App\Repositories\DiscountItem;

use App\Models\DiscountItem;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;

class DiscountItemRepository extends BaseRepository implements DiscountItemRepositoryInterface
{
    public function __construct(DiscountItem $model)
    {
        parent::__construct($model);
    }

    public function getByDiscountId($discountId)
    {
        return $this->model::with(["productColor", "productColor.product"])->where("discount_id", $discountId)->get();
    }

    public function findByProductColorId($discountId, $productColorId)
    {
        return $this->model::where("product_color_id", $productColorId)->where("discount_id", $discountId)->first();
    }

    public function findFirstExpireDiscount()
    {
        return $this->model::where("discount_expire_time", ">", Carbon::now())->whereHas("discount", function ($query) {
            $query->whereDate("end_date", ">", Carbon::now())->where('status', 1);
        })->orderBy("discount_expire_time")->first();
    }

    public function getTopByDiscountId($discountId)
    {
        return $this->model::with(["productColor", "productColor.product"])->where("discount_id", $discountId)->where("top", 1)->get();

    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }
}
