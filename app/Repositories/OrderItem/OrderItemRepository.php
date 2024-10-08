<?php

namespace App\Repositories\OrderItem;

use App\Models\OrderItem;
use App\Repositories\Base\BaseRepository;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    public function __construct(OrderItem $model)
    {
        parent::__construct($model);
    }

    public function createOrderItem($order_id, $product_id, $product_color_id, $count, $price, $dicount, $final_price, $unit_price, $unit_discount)
    {
        $this->create([
            "order_id" => $order_id,
            "product_id" => $product_id,
            "product_color_id" => $product_color_id,
            "count" => $count,
            "price" => $price,
            "dicount" => $dicount,
            "final_price" => $final_price,
            "unit_price" => $unit_price,
            "unit_discount" => $unit_discount,
        ]);
    }

    public function getByOrderId($orderId)
    {
        return $this->model::where("order_id", $orderId)->get();
    }
}
