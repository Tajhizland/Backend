<?php

namespace App\Repositories\Auth;

use App\Repositories\Base\BaseRepositoryInterface;

interface AuthRepositoryInterface extends  BaseRepositoryInterface
{
    public function register($username , $password);
}
