<?php

namespace App\Repositories\Delivery;

use App\Models\Delivery;
use App\Repositories\Base\BaseRepositoryInterface;

interface DeliveryRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function getActiveDelivery();

    public function createDelivery($name, $status,$description ,$price ,$logo);

    public function updateDelivery(Delivery $delivery, $name, $status, $description,$price ,$logo);
}
