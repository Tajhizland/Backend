<?php

namespace App\Services\Favorite;


interface FavoriteServiceInterface
{
    public function addProduct($productId , $userId);
    public function removeProduct($productId , $userId);
    public function showProducts($userId);
 }
