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
        return $this->model::where("user_id", $userId)->paid()->latest("id")->paginate($this->pageSize);
    }

    public function setStatus(Order $order, $status)
    {
        return $order->update(["status" => $status]);
    }

    public function onHoldDataTable()
    {
        return $this->model::hasOnHoldPending()->latest("id")->paginate($this->pageSize);
    }

    public function dataTable()
    {

    }

    public function createOrder($user_id, $order_info_id, $price, $discount,$total_price, $final_price, $status, $payment_method,  $delivery_method, $delivery_price ,$order_date, $tracking_number=null)
    {
       return $this->create(
            [
                "user_id" => $user_id,
                "order_info_id" => $order_info_id,
                "price" => $price,
                "discount" => $discount,
                "final_price" => $final_price,
                "status" => $status,
                "payment_method" => $payment_method,
                "delivery_method" => $delivery_method,
                "delivery_price" => $delivery_price,
                "order_date" => $order_date,
                "tracking_number" => $tracking_number,
            ]
        );
    }
}
