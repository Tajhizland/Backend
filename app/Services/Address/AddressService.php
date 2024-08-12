<?php

namespace App\Services\Address;

use App\Repositories\Address\AddressRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class AddressService implements AddressServiceInterface
{
    public function __construct
    (
        private AddressRepositoryInterface $addressRepository
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
        return $this->addressRepository->findUserAddress($userId);
    }


    public function store($userId, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address)
    {
        $this->addressRepository->createAddress($userId, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address);
    }

    public function update($id, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address)
    {
        $address = $this->addressRepository->findOrFail($id);
        Gate::authorize('update', $address);
    }

    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address)
    {
        $this->addressRepository->updateOrCreateByUserId($userId, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address );
    }
}
