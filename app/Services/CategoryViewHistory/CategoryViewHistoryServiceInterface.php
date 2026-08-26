<?php

namespace App\Services\CategoryViewHistory;

use App\DTOs\CategoryViewHistory\CategoryViewHistoryStoreDto;

interface CategoryViewHistoryServiceInterface
{
    public function store(CategoryViewHistoryStoreDto $dto): mixed;

    public function suggest($userId);
    public function suggestIp($ip);
}
