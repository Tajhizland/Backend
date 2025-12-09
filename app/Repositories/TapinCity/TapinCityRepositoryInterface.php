<?php

namespace App\Repositories\TapinCity;

use App\Repositories\Base\BaseRepositoryInterface;

interface TapinCityRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCity($city);
    public function findByProvince($province);
}
