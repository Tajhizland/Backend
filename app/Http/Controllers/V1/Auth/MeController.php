<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Profile\UpdateProfileRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __construct
    (
        private UserServiceInterface $userService
    )
    {
    }

    public function me(Request $request)
    {
        if (!$request->user())
            return $this->UnauthorizedResponse("Unauthorized");
        return $this->dataResponse(new UserResource($request->user()));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->userService->updateProfile(\Auth::user()->id, $request->get("name"), $request->get("email"), $request->get("gender"), $request->file("avatar"));
        return $this->successResponse(\Lang::get("action.update", ["attr" => \Lang::get("attr.profile")]));
    }
}
