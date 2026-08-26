<?php

namespace App\Services\Profile;

use App\DTOs\Profile\ProfileChangePasswordDto;

interface ProfileServiceInterface
{
    public function changePassword(ProfileChangePasswordDto $dto): mixed;
}
