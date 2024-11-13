<?php

namespace App\Services\Contact;

interface ContactServiceInterface
{
    public function dataTable();
    public function remove($id);
    public function find($id);
    public function store($name , $email , $message ,$cityId,$provinceId);
}
