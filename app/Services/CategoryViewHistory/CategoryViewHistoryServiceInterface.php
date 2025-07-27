<?php

namespace App\Services\CategoryViewHistory;

interface CategoryViewHistoryServiceInterface
{
    public function store($userId, $ip, $categoryId);

    public function suggest($userId);
    public function suggestIp($ip);
}
