<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function updateUser($id,$name , $username,$email , $gender ,$role);
    public function updateProfile($id,$name ,$email , $gender ,$avatar,$last_name,$national_code);
    public function dataTable();
    public function findById($id);
}
