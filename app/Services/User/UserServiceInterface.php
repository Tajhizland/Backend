<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function updateUser($id, $name, $username, $email, $gender, $role, $last_name, $national_code,$role_id);
    public function updateWallet($id, $wallet);

    public function updateProfile($id, $name, $email, $gender, $avatar, $last_name, $national_code);

    public function dataTable();

    public function findById($id);

    public function getHasOrderUser();
    public function getHasNotOrderUser();
    public function getHasActiveCartUser();
    public function adminDataTable();
}
