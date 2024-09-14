<?php

namespace App\Services\Slider;

interface SliderServiceInterface
{
    public function dataTable();
    public function findById($id);
    public function store($title, $url, $status, $image);
    public function update($id, $title, $url, $status, $image);
}
