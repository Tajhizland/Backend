<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Profile\ChangePasswordRequest;
use App\Services\Profile\ProfileServiceInterface;
use Illuminate\Support\Facades\Lang;

class ProfileController extends Controller
{
    public function __construct
    (
        private ProfileServiceInterface $profileService
    )
    {
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->profileService->changePassword($request->get("current_password"), $request->get("new_password"));
        return $this->successResponse(Lang::get("action.change", ["attr" => Lang::get("attr.password")]));
    }
}
