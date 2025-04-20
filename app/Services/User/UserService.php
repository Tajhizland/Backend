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

    public function updateUser($id, $name, $username, $email, $gender, $role)
    {
        return $this->repository->updateUser($id, $name, $username, $email, $gender, $role);
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
}
