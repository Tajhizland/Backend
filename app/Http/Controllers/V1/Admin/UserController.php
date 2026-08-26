<?php

namespace App\Http\Controllers\V1\Admin;

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

    public function findById($id)
    {
        return $this->dataResponse(new UserResource($this->userService->findById($id)));
    }

    public function getByType(GetUserByTypeRequest $request)
    {
        return $this->dataResponseCollection(UserResource::collection($this->userService->getByType($request->get('type'))));
    }


    public function update(UpdateUserRequest $request)
    {
        $this->userService->updateUser($request->get("id"), $request->get("name"), $request->get("username"), $request->get("email"), $request->get("gender"), $request->get("role"), $request->get("last_name"), $request->get("national_code"), $request->get("role_id"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.user")]));
    }

    public function updateWallet(UpdateWalletRequest $request)
    {
        $this->userService->updateWallet($request->get("user_id"), $request->get("wallet"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.user")]));
    }

    public function updateOrCreateAddress(UpdateAddressRequest $request)
    {
        $this->addressService->updateOrCreate($request->get("id"), $request->get("user_id"), $request->get("city_id"), $request->get("province_id"), $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"), $request->get("title"));
        return $this->successResponse(__('action.update', ['attr' => __("attr.address")]));
    }

    public function changeActiveAddress(ChangeActiveAddressRequest $request)
    {
        $this->addressService->changeActiveAddress($request->get("id"), $request->get("user_id"));
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
