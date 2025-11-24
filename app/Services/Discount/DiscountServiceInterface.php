<?php

namespace App\Services\Discount;

interface DiscountServiceInterface
{
    public function dataTable();

    public function store($title, $status, $start_date, $end_date);

    public function update($id, $title, $status, $start_date, $end_date);

    public function find($id);

}
