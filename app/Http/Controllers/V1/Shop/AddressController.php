<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Address\ChangeActiveAddressRequest;
use App\Http\Requests\Shop\Address\UpdateAddresRequest;
use App\Http\Resources\Address\AddressResource;
use App\Services\Address\AddressServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\City\CityResource;
use App\Http\Resources\Province\ProvinceResource;

class AddressController extends Controller
{
    public function __construct
    (
        private readonly AddressServiceInterface $addressService
    )
    {
    }

    public function changeActive(ChangeActiveAddressRequest $request)
    {
        $userId = Auth::user()->id;
        $this->addressService->changeActiveAddress($request->get("id"), $userId);
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function findActive()
    {
        $userId = Auth::user()->id;
        $address = $this->addressService->findActiveByUserId($userId);
        if ($address)
            $address = new AddressResource($address);
        return $this->dataResponse($address);
    }

    public function getAll()
    {
        $userId = Auth::user()->id;
        return $this->dataResponseCollection(AddressResource::collection($this->addressService->getByUserId($userId)));
    }

    public function updateOrCreate(UpdateAddresRequest $request)
    {
        $userId = Auth::user()->id;
        $this->addressService->updateOrCreate($request->get("id"), $userId, $request->get("city_id"), $request->get("province_id"), $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"), $request->get("title"));
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function createOrUpdate(UpdateAddresRequest $request)
    {
        $userId = Auth::user()->id;
        $this->addressService->updateOrCreateByUserId($userId, $request->get("city_id"), $request->get("province_id"), $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"));
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function getCities($id)
    {
        return $this->dataResponseCollection(CityResource::collection($this->addressService->getCities($id)));
    }

    public function getProvinces()
    {
        return $this->dataResponseCollection(ProvinceResource::collection($this->addressService->getProvinces()));
    }
}
