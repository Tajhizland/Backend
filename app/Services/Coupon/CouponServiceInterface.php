<?php

namespace App\Services\Coupon;

interface CouponServiceInterface
{
    public function dataTable();

    public function generate();
    public function check($code , $userId);

    public function find($id);

    public function store(
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
