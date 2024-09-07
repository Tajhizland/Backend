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
    public function updateUser($id,$name, $username, $role)
    {
        return $this->repository->updateUser($id,$name,$username,$role);
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
