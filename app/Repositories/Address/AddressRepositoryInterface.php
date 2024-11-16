<?php

namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\Base\BaseRepositoryInterface;

interface AddressRepositoryInterface extends  BaseRepositoryInterface
{
    public function findUserAddress($userId);
    public function getUserAddress($userId);
    public function createAddress($userId , $cityId,$provinceId,$tell ,$zipCode ,$mobile , $address);
    public function updateOrCreateByUserId($userId , $cityId,$provinceId,$tell ,$zipCode ,$mobile , $address);
    public function updateOrCreateByUserIdFast($userId , $cityId,$provinceId, $address);
    public function updateAddress(Address $addressModal  , $cityId,$provinceId,$tell ,$zipCode ,$mobile , $address);
    public function disableAllAddress($userId);
}
