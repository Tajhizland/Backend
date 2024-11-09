<?php

namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\Base\BaseRepository;

class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }

    public function findUserAddress($userId)
    {
        return $this->model::where("user_id", $userId)->first();
    }

    public function updateOrCreateByUserId($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address)
    {
        return $this->updateOrCreate(["user_id" => $userId],
            [
                "user_id" => $userId,
                "city_id" => $cityId,
                "province_id" => $provinceId,
                "tell" => $tell,
                "zip_code" => $zipCode,
                "mobile" => $mobile,
                "address" => $address,
            ]
        );
    }
    public function updateOrCreateByUserIdFast($userId, $cityId, $provinceId, $address)
    {
        return $this->updateOrCreate(["user_id" => $userId],
            [
                "user_id" => $userId,
                "city_id" => $cityId,
                "province_id" => $provinceId,
                "address" => $address,
            ]
        );
    }

    public function createAddress($userId, $cityId, $provinceId, $tell, $zipCode, $mobile, $address)
    {
        return $this->model::create(
            [
                "user_id" => $userId,
                "city_id" => $cityId,
                "province_id" => $provinceId,
                "tell" => $tell,
                "zip_code" => $zipCode,
                "mobile" => $mobile,
                "address" => $address,
            ]
        );
    }

    public function updateAddress(Address $addressModal, $cityId, $provinceId, $tell, $zipCode, $mobile, $address)
    {
        return $addressModal->update([
            "city_id" => $cityId,
            "prvince_id" => $provinceId,
            "tell" => $tell,
            "zip_code" => $zipCode,
            "mobile" => $mobile,
            "address" => $address,
        ]);
    }
}
