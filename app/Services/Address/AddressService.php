<?php

namespace App\Services\Address;

use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\Province\ProvinceRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class AddressService implements AddressServiceInterface
{
    public function __construct
    (
        private AddressRepositoryInterface  $addressRepository,
        private ProvinceRepositoryInterface $provinceRepository,
        private CityRepositoryInterface     $cityRepository
    )
    {
    }

    public function findById($id)
    {
        $address = $this->addressRepository->findOrFail($id);
        Gate::authorize('view', $address);
        return $address;
    }

    public function findByUserId($userId)
    {
        $address= $this->addressRepository->findUserAddress($userId);
        if(!$address)
        {
            $address=$this->addressRepository->createAddress($userId,"1","1"," "," "," "," ");
        }
        return $address;
    }

    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address)
    {
        $this->addressRepository->updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address);
    }

    public function getCities($provinceId)
    {
        return $this->cityRepository->getByProvinceId($provinceId);
    }

    public function getProvinces()
    {
        return $this->provinceRepository->all();
    }
}
