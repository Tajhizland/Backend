<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register($username, $password)
    {
        $this->create([
            "username" => $username,
            "password" => bcrypt($password),
            "role" => "user"
        ]);
    }

    public function findByUsername($username)
    {
        return $this->get([["username", $username]], 1);
    }

    public function resetPassword($username, $password)
    {
     return $this->model->where("username", $username)->update([
            "password" => bcrypt($password)
        ]);
    }
}
