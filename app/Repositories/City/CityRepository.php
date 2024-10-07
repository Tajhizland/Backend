<?php

namespace App\Repositories\City;

use App\Models\City;
use App\Repositories\Base\BaseRepository;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
    public function getByProvinceId($provinceId)
    {
        return $this->model::where("province_id",$provinceId)->get();
    }
}
