<?php

namespace App\Services\Guaranty;

interface GuarantyServiceInterface
{
    public function dataTable();

    public function findById($id);

    public function findByUrl($url);

    public function getActives();

    public function store($name, $free, $description, $icon, $status ,$url);

    public function update($id, $free, $name, $description, $icon, $status,$url);
}
