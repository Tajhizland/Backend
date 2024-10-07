<?php

namespace App\Repositories\Province;

use App\Models\Province;
use App\Repositories\Base\BaseRepository;

class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    public function __construct(Province $model)
    {
        parent::__construct($model);
    }
}
