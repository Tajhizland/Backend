<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Address\StoreAddressRequest;
use App\Http\Requests\V1\Shop\Address\UpdateAddresRequest;
use App\Http\Resources\V1\Address\AddressResource;
use App\Http\Resources\V1\City\CityCollection;
use App\Http\Resources\V1\Province\ProvinceCollection;
use App\Services\Address\AddressServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AddressController extends Controller
{
    public function __construct
    (
        private AddressServiceInterface $addressService
    )
    {
    }

    public function find()
    {
        $userId = Auth::user()->id;
        return $this->dataResponse(new AddressResource($this->addressService->findByUserId($userId)));
    }

    public function createOrUpdate(UpdateAddresRequest $request)
    {
        $this->addressService->updateOrCreateByUserId($request->get("id"), $request->get("city_id"), $request->get("province_id") , $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"));
        return Lang::get('action.update', ['attr' => Lang::get("attr.address")]);
    }
    public function getCities($id)
    {
        return $this->dataResponseCollection(new CityCollection($this->addressService->getCities($id)));
    }

    public function getProvinces()
    {
        return $this->dataResponseCollection(new ProvinceCollection($this->addressService->getProvinces()));

    }
}
