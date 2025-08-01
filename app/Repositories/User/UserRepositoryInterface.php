<?php

namespace App\Repositories\User;

use App\Repositories\Base\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function register($username, $password, $name, $last_name, $national_code);

    public function resetPassword($username, $password);

    public function findByUsername($username);

    public function dataTable();
    public function todayUserCount();

    public function updateUser($id, $name, $username, $email, $gender, $role,$last_name,$national_code);

}
