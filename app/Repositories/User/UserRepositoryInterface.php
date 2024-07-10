<?php

namespace App\Repositories\User;

use App\Repositories\Base\BaseRepositoryInterface;

interface UserRepositoryInterface extends  BaseRepositoryInterface
{
    public function register($username , $password);
    public function findByUsername($username);

}
