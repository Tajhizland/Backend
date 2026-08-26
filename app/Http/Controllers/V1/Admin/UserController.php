<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Address\AddressChangeActiveDto;
use App\DTOs\Address\AddressUpdateOrCreateDto;
use App\DTOs\User\UserByTypeDto;
use App\DTOs\User\UserUpdateDto;
use App\DTOs\User\UserWalletUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Address\ChangeActiveAddressRequest;
use App\Http\Requests\Admin\Address\UpdateAddressRequest;
use App\Http\Requests\Admin\User\GetUserByTypeRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Requests\Admin\User\UpdateWalletRequest;
use App\Http\Resources\User\UserResource;
use App\Services\Address\AddressServiceInterface;
use App\Services\Auth\Login\LoginServiceInterface;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Order\OrderServiceInterface;
use App\Services\User\UserServiceInterface;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\OnHoldOrder\OnHoldOrderResource;

class UserController extends Controller
{
    public function __construct
    (
        private readonly UserServiceInterface        $userService,
        private readonly AddressServiceInterface     $addressService,
        private readonly OnHoldOrderServiceInterface $onHoldOrderService,
        private readonly OrderServiceInterface       $orderService,
        private readonly LoginServiceInterface       $loginService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(UserResource::collection($this->userService->dataTable()));
    }
    public function adminDataTable()
    {
        return $this->dataResponseCollection(UserResource::collection($this->userService->adminDataTable()));
    }

    public function getAddress($id)
    {
        $response = $this->addressService->getByUserId($id);
        return $this->dataResponseCollection(AddressResource::collection($response));
    }

    public function getOnHoldOrder($id)
    {
        $response = $this->onHoldOrderService->userHoldOnPaginate($id);
        return $this->dataResponseCollection(OnHoldOrderResource::collection($response));
    }

    public function getOrder($id)
    {
        $response = $this->orderService->userOrderPaginate($id);
        return $this->dataResponseCollection(OrderResource::collection($response));
    }

    public function show($id)
    {
        return $this->dataResponse(new UserResource($this->userService->find($id)));
    }

    public function getByType(GetUserByTypeRequest $request)
    {
        $dto = new UserByTypeDto(...$request->validated());
        return $this->dataResponseCollection(UserResource::collection($this->userService->getByType($dto->type)));
    }


    public function update($id, UpdateUserRequest $request)
    {
        $this->userService->updateUser(new UserUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.user")]));
    }

    public function updateWallet(UpdateWalletRequest $request)
    {
        $this->userService->updateWallet(new UserWalletUpdateDto(...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.user")]));
    }

    public function updateOrCreateAddress(UpdateAddressRequest $request)
    {
        $this->addressService->updateOrCreate(new AddressUpdateOrCreateDto(...$request->validated()));
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function changeActiveAddress(ChangeActiveAddressRequest $request)
    {
        $this->addressService->changeActiveAddress(new AddressChangeActiveDto(...$request->validated()));
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function loginUser($id)
    {
        $token = $this->loginService->loginWithUserId($id);
        return $this->dataResponse
        (
            ["token" => $token],
            (__("action.success", ["attr" => __("attr.login")]))
        );
    }
}
