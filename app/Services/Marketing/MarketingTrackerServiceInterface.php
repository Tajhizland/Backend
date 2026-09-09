<?php

namespace App\Services\Marketing;

use App\DTOs\Marketing\MarketingEventDto;
use App\Enums\MarketingEventType;

interface MarketingTrackerServiceInterface
{
    public function track(MarketingEventType $type, ?int $productId = null, array $attributes = []): void;

    public function trackFromClient(MarketingEventDto $dto): void;

    public function logSearch(string $term, int $resultCount, ?int $productCount = null, string $source = 'shop'): void;

    public function normalizeTerm(string $term): string;
}
