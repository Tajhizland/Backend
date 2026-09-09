<?php

namespace App\DTOs\Marketing;

use Carbon\Carbon;

/**
 * بازه و اندازه‌ی خروجی گزارش‌های مارکتینگ.
 *
 * تاریخ‌ها از دیت‌پیکر پنل به صورت میلادی `Y-m-d` می‌آیند و اینجا به ابتدا/انتهای روز
 * گسترش پیدا می‌کنند تا رویدادهای همان روز از قلم نیفتند.
 */
class MarketingReportDto
{
    public const DEFAULT_LIMIT = 20;
    public const MAX_LIMIT = 100;
    public const DEFAULT_PERIOD_DAYS = 30;

    public Carbon $from;
    public Carbon $to;
    public int $limit;

    public function __construct(
        ?string $fromDate = null,
        ?string $toDate = null,
        ?int    $limit = null,
    )
    {
        $from = $fromDate
            ? Carbon::parse($fromDate)->startOfDay()
            : Carbon::now()->subDays(self::DEFAULT_PERIOD_DAYS)->startOfDay();

        $to = $toDate
            ? Carbon::parse($toDate)->endOfDay()
            : Carbon::now()->endOfDay();

        // اگر کاربر بازه را برعکس انتخاب کند، گزارش خالی برنگردد.
        [$this->from, $this->to] = $from->greaterThan($to) ? [$to, $from] : [$from, $to];

        $this->limit = min(max($limit ?? self::DEFAULT_LIMIT, 1), self::MAX_LIMIT);
    }
}
