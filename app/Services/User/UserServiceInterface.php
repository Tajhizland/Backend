<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function updateUser($id,$name , $username ,$role);
    public function dataTable();
    public function findById($id);
}
