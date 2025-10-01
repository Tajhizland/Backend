<?php

namespace App\Services\Role;

interface RoleServiceInterface
{
    public function dataTable();
    public function getAll();

    public function find($id);

    public function store($name, $permission);

    public function update($id, $name, $permission);

}
