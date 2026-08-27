<?php

namespace App\Services\HomePage;

use App\DTOs\HomePage\HomePageData;

interface HomePageServiceInterface
{
    /**
     * پاسخ آماده‌ی صفحه اصلی (در صورت فعال بودن، از کش خوانده می‌شود).
     */
    public function payload(): mixed;

    /**
     * دیتای خام صفحه اصلی، بدون کش.
     */
    public function buildData(): HomePageData;

    /**
     * پاک کردن کش صفحه اصلی.
     */
    public function flushCache(): void;
}
