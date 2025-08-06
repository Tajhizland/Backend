<?php

namespace App\Services\CategoryViewHistory;

use App\Repositories\CategoryViewHistory\CategoryViewHistoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryViewHistoryService implements CategoryViewHistoryServiceInterface
{
    public function __construct
    (
        private CategoryViewHistoryRepositoryInterface $categoryViewHistoryRepository,
        private ProductRepositoryInterface             $productRepository
    )
    {
    }

    public function store($userId, $ip, $categoryId)
    {
        return $this->categoryViewHistoryRepository->create(
            [
                "ip" => $ip,
                "user_id" => $userId,
                "category_id" => $categoryId
            ]
        );
    }

    public function suggest($userId)
    {
        $mostFrequentCategory = $this->categoryViewHistoryRepository->findTops($userId);
        return $this->productRepository->getByCategoryIds($mostFrequentCategory, 0, 6);
    }

    public function suggestIp($ip)
    {
        $mostFrequentCategory = $this->categoryViewHistoryRepository->findTopsByIp($ip);
        return $this->productRepository->getByCategoryIds($mostFrequentCategory, 0, 6);
    }
}
