<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\User\UpdateUserRequest;
use App\Http\Resources\V1\Address\AddressCollection;
use App\Http\Resources\V1\OnHoldOrder\OnHoldOrderCollection;
use App\Http\Resources\V1\Order\OrderCollection;
use App\Http\Resources\V1\User\UserCollection;
use App\Http\Resources\V1\User\UserResource;
use App\Services\Address\AddressServiceInterface;
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
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new UserCollection($this->userService->dataTable()));
    }

    public function getAddress($id)
    {
        $response =$this->addressService->getByUserId($id);
        return $this->dataResponseCollection(new AddressCollection($response));
    }

    public function getOnHoldOrder($id)
    {
        $response =$this->onHoldOrderService->userHoldOnPaginate($id);
        return $this->dataResponseCollection(new OnHoldOrderCollection($response));
    }

    public function getOrder($id)
    {
        $response =$this->orderService->userOrderPaginate($id);
        return $this->dataResponseCollection(new OrderCollection($response));
    }

    public function findById($id)
    {
        return $this->dataResponse(new UserResource($this->userService->findById($id)));
    }

    public function update(UpdateUserRequest $request)
    {
        $this->userService->updateUser($request->get("id"), $request->get("name"), $request->get("username"), $request->get("email"), $request->get("gender"), $request->get("role"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.user")]));
    }
}
