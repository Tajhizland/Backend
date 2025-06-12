<?php

namespace App\Services\CategoryViewHistory;

interface CategoryViewHistoryServiceInterface
{
    public function store($userId, $categoryId);
}
