<?php

namespace App\Services\Address;

interface AddressServiceInterface
{
    public function findById($id);

    public function findActiveByUserId($userId);
    public function getByUserId($userId);
    public function changeActiveAddress($id ,$userId);

    public function updateOrCreate($id,$userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address , $title);
    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address);
    public function updateOrCreateByUserIdFast($userId, $cityId, $provinceId,  $address);

    public function getCities($provinceId);

    public function getProvinces();
}
