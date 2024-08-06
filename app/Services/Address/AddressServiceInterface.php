<?php

namespace App\Services\Address;

interface AddressServiceInterface
{
    public function findById($id);
    public function getByUserId($userId);
    public function setActive($id);
    public function remove($id);
    public function store($userId , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
    public function update($id , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
}
