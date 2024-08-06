<?php

namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\Base\BaseRepository;

class AddressRepository extends  BaseRepository implements  AddressRepositoryInterface
{
public function __construct(Address $model)
{
    parent::__construct($model);
}

    public function getUserAdresses($userId)
    {
        return $this->model::where("user_id",$userId)->latest("id")->get();
    }

    public function deleteAddress(Address $address)
    {
        $address->delete();
    }
    public function setDeActiveAllByUserId($userId)
    {
        $this->model::where("user_id",$userId)->update(["active"=>0]);
    }

    public function setActive(Address $address)
    {
        $address->update(["active" => 1]);
    }

    public function createAddress($userId, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address)
    {
        $this->create(
            [
                "user_id"=>$userId,
                "city_id"=>$cityId,
                "prvince_id"=>$provinceId,
                "tell_code"=>$tellCode,
                "tell"=>$tell,
                "zip_code"=>$zipCode,
                "mobile"=>$mobile,
                "address"=>$address,
            ]
        );
    }

    public function updateAddress(Address $addressModal, $cityId, $provinceId, $tellCode, $tell, $zipCode, $mobile, $address)
    {
        $addressModal->update([
            "city_id"=>$cityId,
            "prvince_id"=>$provinceId,
            "tell_code"=>$tellCode,
            "tell"=>$tell,
            "zip_code"=>$zipCode,
            "mobile"=>$mobile,
            "address"=>$address,
        ]);
    }
}
