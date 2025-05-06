<?php

namespace App\Services\TrustedBrand;

interface TrustedBrandServiceInterface
{
    public function dataTable();

    public function find($id);

    public function delete($id);

    public function store($logo);

    public function update($id, $logo);

}
