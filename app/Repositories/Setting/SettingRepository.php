<?php

namespace App\Repositories\Setting;

use App\Models\Setting;
use App\Repositories\Base\BaseRepository;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }
    public function findSetting()
    {
        return $this->model::first();
    }
}
