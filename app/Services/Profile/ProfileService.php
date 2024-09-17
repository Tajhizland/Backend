<?php

namespace App\Services\Profile;

use App\Exceptions\BreakException;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ProfileService implements ProfileServiceInterface
{
    public function __construct
    (
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function changePassword($currentPassword, $newPassword)
    {
        $user = Auth::user();
        if (!bcrypt($user->password) == $currentPassword)
            throw new BreakException(Lang::get("wrong_password"));
        $this->userRepository->resetPassword($user->username, $newPassword);
    }
}
