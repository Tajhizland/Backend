<?php

namespace App\Repositories\Returned;

use App\Models\Returned;
use App\Repositories\Base\BaseRepositoryInterface;

interface ReturnedRepositoryInterface extends  BaseRepositoryInterface
{
    public function findByOrderItemId(int $orderItemId): mixed;
    public function createReturned($orderId,$orderItemId,$userId,$count,$description,$file): mixed;
    public function setAccept(Returned $returned): mixed;
    public function setReject(Returned $returned): mixed;
    public function dataTable():mixed;


}
