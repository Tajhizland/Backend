<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Address\ChangeActiveAddressRequest;
use App\Http\Requests\V1\Shop\Address\UpdateAddresRequest;
use App\Http\Resources\V1\Address\AddressCollection;
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

    public function changeActive(ChangeActiveAddressRequest $request)
    {
        $userId = Auth::user()->id;
        $this->addressService->changeActiveAddress($request->get("id"), $userId);
        return $this->successResponse(Lang::get('action.update', ['attr' => Lang::get("attr.address")]));
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
        return $this->dataResponseCollection(new AddressCollection($this->addressService->getByUserId($userId)));
    }

    public function updateOrCreate(UpdateAddresRequest $request)
    {
        $userId = Auth::user()->id;
        $this->addressService->updateOrCreate($request->get("id"), $userId, $request->get("city_id"), $request->get("province_id"), $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"), $request->get("title"));
        return $this->successResponse(Lang::get('action.update', ['attr' => Lang::get("attr.address")]));
    }

    public function createOrUpdate(UpdateAddresRequest $request)
    {
        $userId = Auth::user()->id;
        $this->addressService->updateOrCreateByUserId($userId, $request->get("city_id"), $request->get("province_id"), $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"));
        return $this->successResponse(Lang::get('action.update', ['attr' => Lang::get("attr.address")]));
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
