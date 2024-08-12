<?php

namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\Base\BaseRepositoryInterface;

interface AddressRepositoryInterface extends  BaseRepositoryInterface
{
    public function findUserAddress($userId);
    public function createAddress($userId , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
    public function updateOrCreateByUserId($userId , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
    public function updateAddress(Address $addressModal  , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
}
