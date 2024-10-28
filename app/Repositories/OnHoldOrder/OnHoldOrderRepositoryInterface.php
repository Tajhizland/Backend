<?php

namespace App\Repositories\OnHoldOrder;

use App\Models\OnHoldOrder;
use App\Repositories\Base\BaseRepositoryInterface;

interface OnHoldOrderRepositoryInterface extends  BaseRepositoryInterface
{
    public function userOnHoldOrderPaginate($userId);
    public function setReject(OnHoldOrder $onHoldModel);
    public function setAccept(OnHoldOrder $onHoldModel);
    public function dataTable();
    public function todayOnHoldOrderCount();
    public function createOnHoldOrder($orderId);

}
