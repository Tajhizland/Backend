<?php

namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\Base\BaseRepositoryInterface;

interface AddressRepositoryInterface extends  BaseRepositoryInterface
{
    public function getUserAdresses($userId);
    public function deleteAddress(Address $address);
    public function findActiveByUserId($userId);
    public function setActive(Address $address);
    public function setDeActiveAllByUserId($userId);
    public function createAddress($userId , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
    public function updateAddress(Address $addressModal  , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
}
