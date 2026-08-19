<?php

namespace App\Services\Coupon;

interface CouponServiceInterface
{
    public function dataTable();

    public function generate();
    public function check($code , $userId, $totalItemsPrice = null);

    public function find($id);

    public function storeGroup(
        $status,
        $price,
        $percent,
        $user_ids,
        $start_time,
        $end_time,
        $min_order_value,
        $max_order_value,
        $send_sms = false,
        $message = null
    );  public function store(
        $code,
        $status,
        $price,
        $percent,
        $user_id,
        $start_time,
        $end_time,
        $min_order_value,
        $max_order_value
    );

    public function update(
        $id,
        $code,
        $status,
        $price,
        $percent,
        $user_id,
        $start_time,
        $end_time,
        $min_order_value,
        $max_order_value
    );
}
