<?php

namespace App\Services\Profile;

use App\DTOs\Profile\ProfileChangePasswordDto;

use App\Exceptions\BreakException;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

readonly class ProfileService implements ProfileServiceInterface
{
    public function __construct
    (
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function changePassword(ProfileChangePasswordDto $dto): mixed
    {
        $currentPassword = $dto->current_password;
        $newPassword = $dto->new_password;
        $user = Auth::user();
        if (!bcrypt($user->password) == $currentPassword)
            throw new BreakException(Lang::get("wrong_password"));
        $this->userRepository->resetPassword($user->username, $newPassword);
    }
}
