<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Profile\ProfileChangePasswordDto;
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
        $this->profileService->changePassword(new ProfileChangePasswordDto(...$request->validated()));
        return $this->successResponse(__("action.change", ["attr" => __("attr.password")]));
    }
}
