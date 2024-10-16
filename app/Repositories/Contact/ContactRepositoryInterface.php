<?php

namespace App\Repositories\Contact;

use App\Repositories\Base\BaseRepositoryInterface;

interface ContactRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function store($name , $email , $message);
}
