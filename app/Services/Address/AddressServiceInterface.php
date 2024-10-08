<?php

namespace App\Services\Address;

interface AddressServiceInterface
{
    public function findById($id);

    public function findByUserId($userId);

    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address);

    public function getCities($provinceId);

    public function getProvinces();
}
