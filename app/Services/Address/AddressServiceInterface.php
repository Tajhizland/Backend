<?php

namespace App\Services\Address;

use App\DTOs\Address\AddressChangeActiveDto;
use App\DTOs\Address\AddressUpdateOrCreateDto;

interface AddressServiceInterface
{
    public function findById($id);

    public function findActiveByUserId($userId);
    public function getByUserId($userId);
    public function changeActiveAddress(AddressChangeActiveDto $dto): bool;

    public function updateOrCreate(AddressUpdateOrCreateDto $dto): mixed;
    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address);
    public function updateOrCreateByUserIdFast($userId, $cityId, $provinceId,  $address);

    public function getCities($provinceId);

    public function getProvinces();
}
