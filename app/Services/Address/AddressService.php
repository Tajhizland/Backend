<?php

namespace App\Services\Address;

use App\DTOs\Address\AddressChangeActiveDto;
use App\DTOs\Address\AddressUpdateOrCreateDto;

use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\Province\ProvinceRepositoryInterface;
use Illuminate\Support\Facades\Gate;

readonly class AddressService implements AddressServiceInterface
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

    public function changeActiveAddress(AddressChangeActiveDto $dto): bool
    {
        $address = $this->addressRepository->findOrFail($dto->id);
        Gate::authorize('view', $address);
        $this->addressRepository->disableAllAddress($dto->user_id);
        return $this->addressRepository->update($address, ["active" => 1]);
    }

    public function updateOrCreate(AddressUpdateOrCreateDto $dto): mixed
    {
        if ($dto->id) {
            $addressModal = $this->addressRepository->findOrFail($dto->id);
            return $this->addressRepository->update($addressModal, [
                "city_id" => $dto->city_id,
                "title" => $dto->title,
                "province_id" => $dto->province_id,
                "tell" => $dto->tell,
                "zip_code" => $dto->zip_code,
                "mobile" => $dto->mobile,
                "address" => $dto->address,
            ]);
        }

        $this->addressRepository->disableAllAddress($dto->user_id);
        return $this->addressRepository->create([
            "user_id" => $dto->user_id,
            "title" => $dto->title,
            "city_id" => $dto->city_id,
            "province_id" => $dto->province_id,
            "tell" => $dto->tell,
            "zip_code" => $dto->zip_code,
            "mobile" => $dto->mobile,
            "address" => $dto->address,
            "active" => 1,
        ]);
    }
}
