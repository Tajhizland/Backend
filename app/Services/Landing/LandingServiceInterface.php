<?php

namespace App\Services\Landing;

interface LandingServiceInterface
{
    public function store($title, $description, $status, $url);

    public function update($id, $title, $description, $status, $url);

    public function findById($id);

    public function dataTable();

    public function setProduct($landingId, $productId);

    public function setCategory($landingId, $categoryId);

    public function deleteProduct($id);

    public function deleteCategory($id);

    public function findByUrl($url);
    public function getProductByLanding($landingId);
    public function getCategoryByLanding($landingId);


}
