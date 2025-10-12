<?php

namespace App\Services\PhoneBock;

interface PhoneBockServiceInterface
{
    public function dataTable();
    public function store($name, $mobile);
    public function update($id,$name, $mobile);
    public function find($id);
}
