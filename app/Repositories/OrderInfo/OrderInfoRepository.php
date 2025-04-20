<?php

namespace App\Repositories\OrderInfo;

use App\Models\OrderInfo;
use App\Repositories\Base\BaseRepository;

class OrderInfoRepository extends BaseRepository implements OrderInfoRepositoryInterface
{
    public function __construct(OrderInfo $model)
    {
        parent::__construct($model);
    }

    public function createOrderInfo($name, $mobile, $tell, $province_id, $city_id, $address, $zip_code, $last_name, $national_code)
    {
        return $this->create(
            [
                "name" => $name,
                "mobile" => $mobile,
                "tell" => $tell,
                "province_id" => $province_id,
                "city_id" => $city_id,
                "address" => $address,
                "zip_code" => $zip_code,
                "last_name" => $last_name,
                "national_code" => $national_code,
            ]
        );
    }
}
