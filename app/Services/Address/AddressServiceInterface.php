<?php

namespace App\Services\Address;

interface AddressServiceInterface
{
    public function findById($id);
    public function findByUserId($userId);
    public function store($userId , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
    public function updateOrCreateByUserId($userId , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
    public function update($id , $cityId,$provinceId,$tellCode,$tell ,$zipCode ,$mobile , $address);
}
