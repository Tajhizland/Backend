<?php

namespace App\Services\Guaranty;

interface GuarantyServiceInterface
{
    public function dataTable();

    public function findById($id);

    public function findByUrl($url);

    public function getActives();

    public function store($name, $free, $description, $icon, $status ,$url);

    public function update($id,  $name, $free,$description, $icon, $status,$url);

    public function calculatePrice(float $price): float;
    public function getSitemapData();

}
