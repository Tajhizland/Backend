<?php

namespace App\Services\Favorite;

use App\Exceptions\BreakException;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\Lang;

class FavoriteService implements FavoriteServiceInterface
{
    public function __construct
    (
        private FavoriteRepositoryInterface $favoriteRepository,
        private ProductRepositoryInterface  $productRepository
    )
    {
    }

    public function addProduct($productId, $userId)
    {
        $product = $this->productRepository->findById($productId);
        if(!$product)
            throw new BreakException(Lang::get("exceptions.product_not_find"));

        $find = $this->favoriteRepository->findProduct($productId, $userId);
        if ($find)
            throw new BreakException(Lang::get("exceptions.product_already_exist_favorite"));
        return $this->favoriteRepository->addProduct($productId, $userId);
    }

    public function removeProduct($productId, $userId)
    {
        $find = $this->favoriteRepository->findProduct($productId, $userId);
        if (!$find)
            throw new BreakException(Lang::get("exceptions.product_not_exist_favorite"));
        return $this->favoriteRepository->removeProduct($productId, $userId);
    }

    public function showProducts($userId)
    {
        return $this->productRepository->showFavoriteList($userId);
    }
}
