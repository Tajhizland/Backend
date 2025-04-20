<?php

namespace App\Repositories\OrderInfo;

use App\Repositories\Base\BaseRepositoryInterface;

interface OrderInfoRepositoryInterface extends  BaseRepositoryInterface
{
    public function createOrderInfo($name,$mobile,$tell,$province_id,$city_id,$address,$zip_code,$last_name,$national_code);
}
