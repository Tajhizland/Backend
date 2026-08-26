<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Profile\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function __construct
    (
        private readonly UserServiceInterface $userService,
    )
    {
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return $this->successResponse(\__("action.success", ["attr" => \__("attr.logout")]));
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
        return $this->successResponse(__("action.update", ["attr" => __("attr.profile")]));
    }
}
