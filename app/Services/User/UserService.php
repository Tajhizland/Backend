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

    public function updateProfile($id, $name, $email, $gender, $avatar)
    {

        $user = $this->repository->findOrFail($id);
        return ;
        $avatarPath = $user->avatar;
        return $this->updateProfile($id, $name, $email, $gender, $avatarPath);

        if ($avatar) {
            $this->s3Service->remove($avatarPath);
            $avatarPath = $this->s3Service->upload($avatar, "avatar");
        }
        return $this->updateProfile($id, $name, $email, $gender, $avatarPath);
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
