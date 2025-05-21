<?php

namespace App\Repositories\GroupFieldValue;

use App\Repositories\Base\BaseRepositoryInterface;

interface GroupFieldValueRepositoryInterface extends BaseRepositoryInterface
{
    public function findByGroupAndField($groupProductId , $fieldId);
    public function removeByGroupProduct($groupProductId);
    public function removeByFieldId($groupProductId);
}
