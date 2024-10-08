<?php

namespace App\Services\Delivery;

interface DeliveryServiceInterface
{
    public function dataTable();

    public function findById($id);

    public function getActives();

    public function store($name, $status,$description ,$price ,$logo);

    public function update($id, $name, $status, $description,$price ,$logo);
}
