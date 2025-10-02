<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register($username, $password, $name, $last_name, $national_code)
    {
        return $this->create([
            "username" => $username,
            "name" => $name,
            "last_name" => $last_name,
            "national_code" => $national_code,
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
    public function adminDataTable()
    {
        return QueryBuilder::for(User::class)
            ->select("users.*")
            ->where("role","admin")
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

    public function updateUser($id, $name, $username, $email, $gender, $role, $last_name, $national_code,$role_id)
    {
        return $this->model::find($id)->update([
            "name" => $name,
            "username" => $username,
            "last_name" => $last_name,
            "national_code" => $national_code,
            "role" => $role,
            "role_id" => $role_id,
            "gender" => $gender,
            "email" => $email,
        ]);
    }

    public function todayUserCount()
    {
        return $this->model::whereDate('created_at', Carbon::today())->count();

    }

    public function getHasOrderUser()
    {
        return $this->model::whereHas("order",function ($query){
            $query->paid();
        })->get();
    }

    public function getHasNotOrderUser()
    {
        return $this->model::whereDoesntHave("order",function ($query){
            $query->paid();
        })->get();

    }

    public function getHasActiveCartUser()
    {
        return $this->model::whereHas("activeCart")->get();

    }
}
