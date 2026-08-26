<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Profile\ChangePasswordRequest;
use App\Services\Profile\ProfileServiceInterface;

class ProfileController extends Controller
{
    public function __construct
    (
        private readonly ProfileServiceInterface $profileService
    )
    {
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->profileService->changePassword($request->get("current_password"), $request->get("new_password"));
        return $this->successResponse(__("action.change", ["attr" => __("attr.password")]));
    }
}
