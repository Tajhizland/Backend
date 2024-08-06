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

    public function getByUserId($userId)
    {
        return $this->addressRepository->getUserAdresses($userId);
    }

    public function setActive($id)
    {
        $address = $this->addressRepository->findOrFail($id);
        Gate::authorize('update', $address);
        $this->addressRepository->setDeActiveAllByUserId($address->user_id);
        return  $this->addressRepository->setActive($address);
    }

    public function remove($id)
    {
        $address = $this->addressRepository->findOrFail($id);
        Gate::authorize('delete', $address);
        return  $this->addressRepository->delete($address);
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

}
