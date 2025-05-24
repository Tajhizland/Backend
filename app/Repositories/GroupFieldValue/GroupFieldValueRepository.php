<?php

namespace App\Repositories\GroupFieldValue;

use App\Models\GroupFieldValue;
use App\Repositories\Base\BaseRepository;

class GroupFieldValueRepository extends BaseRepository implements GroupFieldValueRepositoryInterface
{
    public function __construct(GroupFieldValue $model)
    {
        parent::__construct($model);
    }

    public function removeByGroupProduct($groupProductId)
    {
        return $this->model::where("group_product_id", $groupProductId)->delete();
    }

    public function removeByFieldId($groupProductId)
    {
        return $this->model::where("group_field_id", $groupProductId)->delete();
    }

    public function findByGroupAndField($groupProductId, $fieldId)
    {
        return $this->model::where("group_field_id", $fieldId)->where("group_product_id",$groupProductId )->first();
    }
}
