<?php

namespace App\Services\CastCategory;

interface CastCategoryServiceInterface
{
    public function dataTable();

    public function find($id);

    public function store($name, $status);

    public function get();

    public function update($id, $name, $status);


}
