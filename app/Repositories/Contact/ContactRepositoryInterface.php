<?php

namespace App\Repositories\Contact;

use App\Repositories\Brand\BrandRepositoryInterface;

interface ContactRepositoryInterface extends  BrandRepositoryInterface
{
    public function dataTable();
    public function store($name , $email , $message);
}
