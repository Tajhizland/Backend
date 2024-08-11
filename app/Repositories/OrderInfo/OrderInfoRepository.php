<?php

namespace App\Repositories\OrderInfo;

use App\Repositories\Base\BaseRepository;

class OrderInfoRepository extends BaseRepository implements OrderInfoRepositoryInterface
{

    public function createOrderInfo($name, $mobile, $tell, $province_id, $city_id, $address, $zip_code)
    {
        return $this->create(
            [
                "name" => $name,
                "name" => $mobile,
                "name" => $tell,
                "name" => $province_id,
                "name" => $city_id,
                "name" => $address,
                "name" => $zip_code,
            ]
        );
    }
}
