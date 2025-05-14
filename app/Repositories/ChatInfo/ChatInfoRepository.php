<?php

namespace App\Repositories\ChatInfo;

use App\Models\ChatInfo;
use App\Repositories\Base\BaseRepository;

class ChatInfoRepository extends BaseRepository implements ChatInfoRepositoryInterface
{
    public function __construct(ChatInfo $model)
    {
        parent::__construct($model);
    }

    public function findByUserId($userId)
    {
        return $this->model::where("user_id", $userId)->first();
    }
}
