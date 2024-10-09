<?php

namespace App\Repositories\OrderItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface OrderItemRepositoryInterface extends  BaseRepositoryInterface
{
    public function createOrderItem($order_id,$product_id,$product_color_id,$count,$price,$dicount,$final_price );

    public function getByOrderId($orderId);
}
