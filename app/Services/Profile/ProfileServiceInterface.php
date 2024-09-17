<?php

namespace App\Services\Profile;

interface ProfileServiceInterface
{
    public function changePassword($currentPassword , $newPassword);
}
