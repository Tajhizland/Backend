<?php

namespace App\Repositories\User;

use App\Repositories\Base\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function register($username, $password);

    public function resetPassword($username, $password);

    public function findByUsername($username);

    public function dataTable();

    public function updateUser($id, $name, $username, $email, $gender, $role);

}
