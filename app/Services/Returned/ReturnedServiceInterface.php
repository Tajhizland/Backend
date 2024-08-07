<?php

namespace App\Services\Returned;

interface ReturnedServiceInterface
{
    public function store($orderId, $orderItemId, $userId, $count, $description, $file);
    public function accept($id);
    public function reject($id);
    public function dataTable();
}
