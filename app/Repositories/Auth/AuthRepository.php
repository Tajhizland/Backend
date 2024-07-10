<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Base\BaseRepository;

class AuthRepository extends BaseRepository implements  AuthRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register($username, $password)
    {
        $this->model->create([
            "username"=>$username,
            "password"=>bcrypt($password),
            "role"=>"user"
        ]);
    }
}
