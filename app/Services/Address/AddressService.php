<?php

namespace App\Services\Address;

use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\Province\ProvinceRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $address=$this->addressRepository->findActiveByUserId($userId);
        if(!$address)
            throw new NotFoundHttpException();
        return $address;
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

    public function updateOrCreate($id, $userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address)
    {
        if ($id) {
            $addressModal = $this->addressRepository->findOrFail($id);
            return $this->addressRepository->update($addressModal, [
                "city_id" => $cityId,
                "province_id" => $provinceId,
                "tell" => $tell,
                "zip_code" => $zipCode,
                "mobile" => $mobile,
                "address" => $address,
            ]);
        } else {
            return $this->addressRepository->create([
                "user_id" => $userId,
                "city_id" => $cityId,
                "province_id" => $provinceId,
                "tell" => $tell,
                "zip_code" => $zipCode,
                "mobile" => $mobile,
                "address" => $address,
            ]);
        }
    }
}
