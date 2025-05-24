<?php

namespace App\Repositories\GroupProduct;

use App\Models\GroupProduct;
use App\Repositories\Base\BaseRepository;

class GroupProductRepository extends BaseRepository implements GroupProductRepositoryInterface
{
    public function __construct(GroupProduct $model)
    {
        parent::__construct($model);
    }

    public function findByGroupAndProduct($productId, $groupId)
    {
        return $this->model::where("product_id",$productId)->where("group_id",$groupId)->first();
    }

    public function getByGroupId($groupId)
    {
        return $this->model::with(["product"])->where("group_id",$groupId)->get();
    }

    public function getByGroupIdWithValue($groupId)
    {
        return $this->model::with(["product" , "value"])->where("group_id",$groupId)->get();

    }
}
