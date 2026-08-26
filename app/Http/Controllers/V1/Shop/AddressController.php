<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Address\AddressChangeActiveDto;
use App\DTOs\Address\AddressUpdateOrCreateDto;
use App\DTOs\Address\AddressCreateOrUpdateDto;
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
        $this->addressService->changeActiveAddress(new AddressChangeActiveDto(Auth::user()->id, ...$request->validated()));
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
        $this->addressService->updateOrCreate(new AddressUpdateOrCreateDto(Auth::user()->id, ...$request->validated()));
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function createOrUpdate(UpdateAddresRequest $request)
    {
        $dto = new AddressCreateOrUpdateDto(Auth::user()->id, ...collect($request->validated())->except(['id', 'title'])->all());
        $this->addressService->updateOrCreateByUserId($dto->userId, $dto->city_id, $dto->province_id, $dto->tell, $dto->zip_code, $dto->mobile, $dto->address);
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
