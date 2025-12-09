<?php

namespace App\Repositories\TapinCity;

use App\Models\TapinCity;
use App\Repositories\Base\BaseRepository;
class TapinCityRepository extends BaseRepository implements TapinCityRepositoryInterface
{
    public function __construct(TapinCity $model)
    {
        parent::__construct($model);
    }

    public function findByCity($city)
    {
        return $this->model::where("city",$city)->first();
    }

    public function findByProvince($province)
    {
        return $this->model::where("province",$province)->first();
    }
}
