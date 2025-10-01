<?php

namespace App\Services\Permission;

interface PermissionServiceInterface
{
    public function dataTable();
    public function getAll();


    public function find($id);

    public function store($name, $value);

    public function update($id, $name, $value);

}
