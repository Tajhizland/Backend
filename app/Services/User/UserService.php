<?php

namespace App\Services\User;

use App\Repositories\User\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    public function __construct
    (
        private UserRepositoryInterface $repository
    )
    {
    }
}
