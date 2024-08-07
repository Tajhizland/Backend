<?php

namespace App\Services\Gateway;

interface GatewayServiceInterface
{
    public function dataTable();

    public function findById($id);

    public function store($name, $status,$description);

    public function update($id, $name, $status, $description);
}
