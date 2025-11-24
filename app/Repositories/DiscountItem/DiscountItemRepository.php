<?php

namespace App\Repositories\DiscountItem;

use App\Models\DiscountItem;
use App\Repositories\Base\BaseRepository;

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
}
