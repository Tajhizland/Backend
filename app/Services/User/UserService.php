<?php

namespace App\Services\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct
    (
        private UserRepositoryInterface $repository,
        private S3ServiceInterface      $s3Service,
    )
    {
    }

    public function updateUser($id, $name, $username, $email, $gender, $role, $last_name, $national_code,$role_id)
    {
        return $this->repository->updateUser($id, $name, $username, $email, $gender, $role, $last_name, $national_code,$role_id);
    }

    public function updateProfile($id, $name, $email, $gender, $avatar, $last_name, $national_code)
    {
        $user = $this->repository->findOrFail($id);
        $avatarPath = $user->avatar;
        if ($avatar) {
            if ($avatarPath) {
                $this->s3Service->remove($avatarPath);
            }
            $avatarPath = $this->s3Service->upload($avatar, "avatar");
        }
        return $this->repository->update($user, [
            "name" => $name,
            "email" => $email,
            "gender" => $gender,
            "avatar" => $avatarPath,
            "last_name" => $last_name,
            "national_code" => $national_code,
        ]);
    }

    public function findById($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->repository->dataTable();
    }

    public function updateWallet($id, $wallet)
    {
        $user = $this->repository->findOrFail($id);
        return $this->repository->update($user, ["wallet" => $wallet]);
    }

    public function getHasOrderUser()
    {
        return $this->repository->getHasOrderUser();
    }

    public function getHasNotOrderUser()
    {
        return $this->repository->getHasNotOrderUser();
    }

    public function getHasActiveCartUser()
    {
        return $this->repository->getHasActiveCartUser();
    }

    public function adminDataTable()
    {
        return $this->repository->adminDataTable();
    }

    public function getByIds($userIds)
    {
        return $this->repository->getByIds($userIds);
    }
    public function getAll()
    {
        return $this->repository->all();
    }
}
