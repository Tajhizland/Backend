<?php

namespace App\Services\Discount;

interface DiscountServiceInterface
{
    public function dataTable();

    public function store($title, $status, $start_date, $end_date);

    public function update($id, $title, $status, $start_date, $end_date);

    public function find($id);

    public function getItem($id);
    public function getTopItem($id);

    public function deleteItem($id);

    public function setItem($discountId, $discount);

    public function updateItem($discount);

    public function sort($discounts);


}
