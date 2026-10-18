<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\InstagramProfile\InstagramProfileUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstagramProfile\UpdateInstagramProfileRequest;
use App\Http\Resources\InstagramProfile\InstagramProfileResource;
use App\Services\InstagramProfile\InstagramProfileServiceInterface;

class InstagramProfileController extends Controller
{
    public function __construct(private readonly InstagramProfileServiceInterface $profileService) {}

    public function show()
    {
        return $this->dataResponse(new InstagramProfileResource($this->profileService->get()));
    }

    public function update(UpdateInstagramProfileRequest $request)
    {
        $profile = $this->profileService->update(new InstagramProfileUpdateDto(...$request->validated()));

        return $this->dataResponse(new InstagramProfileResource($profile), 'پروفایل اینستاگرام ذخیره شد.');
    }
}
