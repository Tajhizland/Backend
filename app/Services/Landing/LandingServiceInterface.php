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
    public function getProductByLanding($landingId);
    public function getCategoryByLanding($landingId);
    public function getBanner($landingId);
    public function deleteBanner($id);
    public function setBanner($image,$url,$landingId,$slider);

    public function findByUrl($url);
}
