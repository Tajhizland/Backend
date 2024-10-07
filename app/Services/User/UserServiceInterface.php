<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function updateUser($id,$name , $username,$email , $gender ,$role);
    public function updateProfile($id,$name ,$email , $gender ,$avatar);
    public function dataTable();
    public function findById($id);
}
