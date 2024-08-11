<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Address\StoreAddressRequest;
use App\Http\Requests\V1\Shop\Address\UpdateAddresRequest;
use App\Services\Address\AddressServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AddressController extends Controller
{
    public function __construct
    (
        private  AddressServiceInterface $addressService
    )
    {
    }

    public function find($id)
    {
        return $this->addressService->findById($id);
    }
    public function getAll()
    {
        $user= Auth::user();
        $this->addressService->getByUserId($user->id);
    }
    public function remove($id)
    {
        $user= Auth::user();
        $this->addressService->remove($id);
        return Lang::get('action.remove', ['attr' => Lang::get("attr.address")]);

    }
    public function setActive($id)
    {
        $this->addressService->setActive($id);
        return Lang::get('action.update', ['attr' => Lang::get("attr.active_address")]);

    }
    public function store(StoreAddressRequest $request)
    {
        $user= Auth::user();
        $this->addressService->store($user->id ,$request->get("city_id"),$request->get("province_id"),$request->get("tell_code"),$request->get("tell"),$request->get("zip_code"),$request->get("mobile"),$request->get("address"));
        return Lang::get('action.store', ['attr' => Lang::get("attr.address")]);
    }
    public function update(UpdateAddresRequest $request)
    {
        $this->addressService->update($request->get("id"),$request->get("city_id"),$request->get("province_id"),$request->get("tell_code"),$request->get("tell"),$request->get("zip_code"),$request->get("mobile"),$request->get("address"));
        return Lang::get('action.update', ['attr' => Lang::get("attr.address")]);
    }
}