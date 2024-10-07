<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\User\UpdateUserRequest;
use App\Http\Resources\V1\User\UserCollection;
use App\Http\Resources\V1\User\UserResource;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    public function __construct
    (
        private UserServiceInterface $userService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new UserCollection($this->userService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new UserResource($this->userService->findById($id)));
    }

    public function update(UpdateUserRequest $request)
    {
        $this->userService->updateUser($request->get("id"), $request->get("name"), $request->get("username"), $request->get("email"), $request->get("gender"), $request->get("role"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.user")]));
    }
}
