<?php

namespace App\DTOs\CategoryViewHistory;

class CategoryViewHistoryStoreDto
{
    public function __construct(
        public mixed $userId,
        public ?string $ip,
        public int $category_id,
    )
    {
    }
}
