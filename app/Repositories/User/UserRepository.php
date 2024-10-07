<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register($username, $password)
    {
        return $this->create([
            "username" => $username,
            "password" => bcrypt($password),
            "role" => "user"
        ]);
    }

    public function findByUsername($username)
    {
        return $this->get([["username", $username]], 1);
    }

    public function dataTable()
    {
        return QueryBuilder::for(User::class)
            ->select("users.*")
            ->allowedFilters(['name', 'role', 'username', 'id', 'created_at'])
            ->allowedSorts(['name', 'role', 'username', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function resetPassword($username, $password)
    {
        return $this->model->where("username", $username)->update([
            "password" => bcrypt($password)
        ]);
    }

    public function updateUser($id, $name, $username, $email, $gender, $role)
    {
        return $this->model::find($id)->update([
            "name" => $name,
            "username" => $username,
            "role" => $role,
            "gender" => $gender,
            "email" => $email,
        ]);
    }
}
