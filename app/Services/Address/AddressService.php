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

    public function findActiveByUserId($userId)
    {
        return $this->addressRepository->findActiveByUserId($userId);
    }

    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address)
    {
        $this->addressRepository->updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address);
    }

    public function updateOrCreateByUserIdFast($userId, $cityId, $provinceId, $address)
    {
        $this->addressRepository->updateOrCreateByUserIdFast($userId, $cityId, $provinceId, $address);
    }

    public function getCities($provinceId)
    {
        return $this->cityRepository->getByProvinceId($provinceId);
    }

    public function getProvinces()
    {
        return $this->provinceRepository->all();
    }

    public function getByUserId($userId)
    {
        return $this->addressRepository->getUserAddress($userId);
    }

    public function changeActiveAddress($id, $userId)
    {
        $address = $this->addressRepository->findOrFail($id);
        Gate::authorize('view', $address);
        $this->addressRepository->disableAllAddress($userId);
        return $this->addressRepository->update($address, ["active" => 1]);
    }

    public function updateOrCreate($id, $userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address ,$title)
    {
        if ($id) {
            $addressModal = $this->addressRepository->findOrFail($id);
            return $this->addressRepository->update($addressModal, [
                "city_id" => $cityId,
                "title" => $title,
                "province_id" => $provinceId,
                "tell" => $tell,
                "zip_code" => $zipCode,
                "mobile" => $mobile,
                "address" => $address,
            ]);
        } else {
            $this->addressRepository->disableAllAddress($userId);
            return $this->addressRepository->create([
                "user_id" => $userId,
                "title" => $title,
                "city_id" => $cityId,
                "province_id" => $provinceId,
                "tell" => $tell,
                "zip_code" => $zipCode,
                "mobile" => $mobile,
                "address" => $address,
                "active" => 1
            ]);
        }
    }
}
