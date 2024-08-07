<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
    public function userOrderPaginate($userId)
    {
        return $this->model::where("user_id",$userId)->paid()->latest("id")->paginate($this->pageSize);
    }
    public function setStatus(Order $order ,$status)
    {
        return $order->update(["status" => $status]);
    }
    public function onHoldDataTable()
    {
        return $this->model::hasOnHoldPending()->latest("id")->paginate($this->pageSize);
    }
    public function dataTable(){

    }
}
