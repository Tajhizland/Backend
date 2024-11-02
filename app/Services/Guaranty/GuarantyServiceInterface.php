<?php

namespace App\Services\Guaranty;

interface GuarantyServiceInterface
{
    public function dataTable();

    public function findById($id);

    public function store($name, $description, $icon, $status);

    public function update($id, $name, $description, $icon, $status);
}
