<?php

namespace App\Services\CategoryViewHistory;

use App\Repositories\CategoryViewHistory\CategoryViewHistoryRepositoryInterface;

class CategoryViewHistoryService implements CategoryViewHistoryServiceInterface
{
    public function __construct
    (
        private CategoryViewHistoryRepositoryInterface $categoryViewHistoryRepository
    )
    {
    }

    public function store($userId, $categoryId)
    {
        return $this->categoryViewHistoryRepository->create(
            [
                "user_id" => $userId,
                "category_id" => $categoryId
            ]
        );
    }
}
