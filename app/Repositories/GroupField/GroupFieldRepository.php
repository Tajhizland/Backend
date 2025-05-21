<?php

namespace App\Repositories\GroupField;

use App\Models\GroupField;
use App\Repositories\Base\BaseRepository;

class GroupFieldRepository extends BaseRepository implements GroupFieldRepositoryInterface
{
    public function __construct(GroupField $model)
    {
        parent::__construct($model);
    }

    public function getByGroupId($groupId)
    {
        return $this->model::where("group_id",$groupId)->get();
    }
}
