<?php

namespace App\Services\User;

use App\DTOs\User\UserUpdateDto;
use App\DTOs\User\UserWalletUpdateDto;

interface UserServiceInterface
{
    public function updateUser(UserUpdateDto $dto): mixed;
    public function updateWallet(UserWalletUpdateDto $dto): bool;

    public function updateProfile($id, $name, $email, $gender, $avatar, $last_name, $national_code);

    public function dataTable();

    public function find(int $id): mixed;

    public function getHasOrderUser();
    public function getHasNotOrderUser();
    public function getHasActiveCartUser();
    public function getByIds($userIds);
    public function adminDataTable();
    public function getByType($type);

}
