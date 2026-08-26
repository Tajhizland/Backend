<?php

namespace App\Services\Favorite;

use App\DTOs\Favorite\FavoriteProductDto;


interface FavoriteServiceInterface
{
    public function addProduct(FavoriteProductDto $dto): mixed;
    public function removeProduct(FavoriteProductDto $dto): mixed;
    public function showProducts($userId);
 }
