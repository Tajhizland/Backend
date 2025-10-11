<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Address\ChangeActiveAddressRequest;
use App\Http\Requests\V1\Admin\Address\UpdateAddressRequest;
use App\Http\Requests\V1\Admin\User\UpdateUserRequest;
use App\Http\Requests\V1\Admin\User\UpdateWalletRequest;
use App\Http\Resources\V1\Address\AddressCollection;
use App\Http\Resources\V1\OnHoldOrder\OnHoldOrderCollection;
use App\Http\Resources\V1\Order\OrderCollection;
use App\Http\Resources\V1\User\UserCollection;
use App\Http\Resources\V1\User\UserResource;
use App\Services\Address\AddressServiceInterface;
use App\Services\Auth\Login\LoginServiceInterface;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Order\OrderServiceInterface;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    public function __construct
    (
        private UserServiceInterface        $userService,
        private AddressServiceInterface     $addressService,
        private OnHoldOrderServiceInterface $onHoldOrderService,
        private OrderServiceInterface       $orderService,
        private LoginServiceInterface       $loginService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new UserCollection($this->userService->dataTable()));
    }
    public function adminDataTable()
    {
        return $this->dataResponseCollection(new UserCollection($this->userService->adminDataTable()));
    }

    public function getAddress($id)
    {
        $response = $this->addressService->getByUserId($id);
        return $this->dataResponseCollection(new AddressCollection($response));
    }

    public function getOnHoldOrder($id)
    {
        $response = $this->onHoldOrderService->userHoldOnPaginate($id);
        return $this->dataResponseCollection(new OnHoldOrderCollection($response));
    }

    public function getOrder($id)
    {
        $response = $this->orderService->userOrderPaginate($id);
        return $this->dataResponseCollection(new OrderCollection($response));
    }

    public function findById($id)
    {
        return $this->dataResponse(new UserResource($this->userService->findById($id)));
    }

    public function all()
    {
        return $this->dataResponseCollection(new UserCollection($this->userService->getAll()));
    }


    public function update(UpdateUserRequest $request)
    {
        $this->userService->updateUser($request->get("id"), $request->get("name"), $request->get("username"), $request->get("email"), $request->get("gender"), $request->get("role"), $request->get("last_name"), $request->get("national_code"), $request->get("role_id"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.user")]));
    }

    public function updateWallet(UpdateWalletRequest $request)
    {
        $this->userService->updateWallet($request->get("id"), $request->get("wallet"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.user")]));
    }

    public function updateOrCreateAddress(UpdateAddressRequest $request)
    {
        $this->addressService->updateOrCreate($request->get("id"), $request->get("user_id"), $request->get("city_id"), $request->get("province_id"), $request->get("tell"), $request->get("zip_code"), $request->get("mobile"), $request->get("address"), $request->get("title"));
        return $this->successResponse(Lang::get('action.update', ['attr' => Lang::get("attr.address")]));
    }

    public function changeActiveAddress(ChangeActiveAddressRequest $request)
    {
        $this->addressService->changeActiveAddress($request->get("id"), $request->get("user_id"));
        return $this->successResponse(Lang::get('action.update', ['attr' => Lang::get("attr.address")]));
    }

    public function loginUser($id)
    {
        $token = $this->loginService->loginWithUserId($id);
        return $this->dataResponse
        (
            ["token" => $token],
            (Lang::get("action.success", ["attr" => Lang::get("attr.login")]))
        );
    }
}
