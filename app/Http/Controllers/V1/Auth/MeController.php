<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Profile\UpdateProfileRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class MeController extends Controller
{
    public function __construct
    (
        private UserServiceInterface $userService,
    )
    {
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return $this->successResponse(\Lang::get("action.success", ["attr" => \Lang::get("attr.logout")]));
    }

    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user)
            return $this->UnauthorizedResponse("Unauthorized");

        $user->load('roles.permissions');

        return $this->dataResponse(new UserResource($user));
    }

    public function update(UpdateProfileRequest $request)
    {

        $this->userService->updateProfile(Auth::user()->id, $request->get("name"), $request->get("email"), $request->get("gender"), $request->file("avatar"), $request->get("last_name"), $request->get("national_code"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.profile")]));
    }
}
