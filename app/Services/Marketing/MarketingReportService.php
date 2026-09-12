<?php

namespace App\Services\Marketing;

use App\DTOs\Marketing\MarketingReportDto;
use App\Enums\DeviceType;
use App\Enums\MarketingEventType;
use App\Repositories\Footprint\FootprintRepositoryInterface;
use App\Repositories\MarketingEvent\MarketingEventRepositoryInterface;
use App\Repositories\SearchLog\SearchLogRepositoryInterface;

/**
 * مسیر خواندنِ داده‌های مارکتینگ: همه‌ی گزارش‌های پنل از اینجا می‌آیند.
 */
readonly class MarketingReportService implements MarketingReportServiceInterface
{
    /**
     * حداقل بازدید لازم برای اینکه محصولی وارد گزارش نرخ تبدیل شود.
     * بدون این آستانه، محصولی با ۱ بازدید و ۰ سبد، صدرنشین «بدترین نرخ تبدیل» می‌شد.
     */
    private const MIN_VIEWS_FOR_CONVERSION = 10;

    public function __construct(
        private MarketingEventRepositoryInterface $marketingEventRepository,
        private SearchLogRepositoryInterface      $searchLogRepository,
        private FootprintRepositoryInterface      $footprintRepository,
    )
    {
    }

    public function overview(MarketingReportDto $dto): array
    {
        $productViews = $this->marketingEventRepository->countEvents(MarketingEventType::ProductView->value, $dto->from, $dto->to);
        $addToCart = $this->marketingEventRepository->countEvents(MarketingEventType::AddToCart->value, $dto->from, $dto->to);
        $compare = $this->marketingEventRepository->countEvents(MarketingEventType::Compare->value, $dto->from, $dto->to);
        $favorite = $this->marketingEventRepository->countEvents(MarketingEventType::Favorite->value, $dto->from, $dto->to);
        $searchTotals = $this->searchLogRepository->totals($dto->from, $dto->to);

        return [
            'summary' => [
                'productViews' => $productViews,
                'viewVisitors' => $this->marketingEventRepository->countVisitors(MarketingEventType::ProductView->value, $dto->from, $dto->to),
                'addToCart' => $addToCart,
                'compare' => $compare,
                'favorite' => $favorite,
                'searches' => $searchTotals['total'],
                'uniqueTerms' => $searchTotals['uniqueTerms'],
                'zeroResults' => $searchTotals['zeroResults'],
                'noProductResults' => $searchTotals['noProductResults'],
                'zeroResultRate' => $searchTotals['zeroResultRate'],
                'viewToCartRate' => $productViews > 0 ? round($addToCart / $productViews * 100, 1) : 0.0,
            ],
            'charts' => [
                'productViews' => $this->marketingEventRepository->dailyCounts(MarketingEventType::ProductView->value, $dto->from, $dto->to),
                'addToCart' => $this->marketingEventRepository->dailyCounts(MarketingEventType::AddToCart->value, $dto->from, $dto->to),
                'compare' => $this->marketingEventRepository->dailyCounts(MarketingEventType::Compare->value, $dto->from, $dto->to),
                'searches' => $this->searchLogRepository->dailyCounts($dto->from, $dto->to),
            ],
        ];
    }

    public function topViewedProducts(MarketingReportDto $dto)
    {
        return $this->topProducts(MarketingEventType::ProductView, $dto);
    }

    public function topCartProducts(MarketingReportDto $dto)
    {
        return $this->topProducts(MarketingEventType::AddToCart, $dto);
    }

    public function topComparedProducts(MarketingReportDto $dto)
    {
        return $this->topProducts(MarketingEventType::Compare, $dto);
    }

    public function topFavoriteProducts(MarketingReportDto $dto)
    {
        return $this->topProducts(MarketingEventType::Favorite, $dto);
    }

    public function topCategories(MarketingReportDto $dto)
    {
        return $this->marketingEventRepository->topCategories(
            MarketingEventType::ProductView->value,
            $dto->from,
            $dto->to,
            $dto->limit
        );
    }

    public function topSearches(MarketingReportDto $dto)
    {
        return $this->searchLogRepository->topTerms($dto->from, $dto->to, $dto->limit);
    }

    public function zeroResultSearches(MarketingReportDto $dto)
    {
        return $this->searchLogRepository->zeroResultTerms($dto->from, $dto->to, $dto->limit);
    }

    /**
     * محصولاتی که ترافیک می‌گیرند ولی کمترین نرخ تبدیل بازدید به سبد را دارند؛
     * یعنی جایی که اصلاح قیمت، عکس یا توضیحات بیشترین اثر را دارد.
     */
    public function conversionOpportunities(MarketingReportDto $dto)
    {
        return $this->marketingEventRepository->engagementMatrix(
            $dto->from,
            $dto->to,
            $dto->limit,
            self::MIN_VIEWS_FOR_CONVERSION,
            'conversion'
        );
    }

    public function unmetDemand(MarketingReportDto $dto)
    {
        return $this->marketingEventRepository->unmetDemand($dto->from, $dto->to, $dto->limit);
    }

    /**
     * گزارش دستگاه: کاربران با چه چیزی وارد سایت می‌شوند.
     *
     * مبنای سهم، جدول بازدید صفحات (footprints) است نه رویدادهای محصولی، چون هر
     * تغییر مسیر در سایت آنجا ثبت می‌شود و کامل‌ترین تصویر از ورود کاربر را می‌دهد.
     * ربات‌ها جدا شمرده می‌شوند ولی در سهمِ کاربران واقعی نمی‌آیند تا درصدها واقعی بماند.
     */
    public function deviceReport(MarketingReportDto $dto): array
    {
        $breakdown = $this->footprintRepository->deviceBreakdown($dto->from, $dto->to);

        $byDevice = $breakdown->keyBy('device');
        $humanTotal = $breakdown
            ->reject(fn($row) => $row->device === DeviceType::Bot->value)
            ->sum('total');

        $share = collect(DeviceType::reportOrder())
            ->map(function (DeviceType $device) use ($byDevice, $humanTotal) {
                $row = $byDevice->get($device->value);
                $total = (int)($row->total ?? 0);
                $isBot = $device === DeviceType::Bot;

                return [
                    'device' => $device->value,
                    'label' => $device->label(),
                    'total' => $total,
                    'visitors' => (int)($row->visitors ?? 0),
                    // سهم ربات‌ها معنا ندارد؛ برای همین null است نه صفر.
                    'share' => $isBot || $humanTotal === 0 ? null : round($total / $humanTotal * 100, 1),
                ];
            })
            ->filter(fn(array $row) => $row['total'] > 0)
            ->values()
            ->all();

        return [
            'share' => $share,
            'humanTotal' => (int)$humanTotal,
            'trend' => $this->footprintRepository->deviceDailyCounts($dto->from, $dto->to),
            'platforms' => $this->footprintRepository->attributeBreakdown('platform', $dto->from, $dto->to, $dto->limit),
            'browsers' => $this->footprintRepository->attributeBreakdown('browser', $dto->from, $dto->to, $dto->limit),
            'conversion' => $this->deviceConversion($dto),
        ];
    }

    /**
     * نرخ تبدیل هر دستگاه؛ مقادیر درصدی همین‌جا حساب می‌شوند تا فرانت فقط نمایش بدهد.
     */
    private function deviceConversion(MarketingReportDto $dto): array
    {
        return $this->marketingEventRepository->deviceConversion($dto->from, $dto->to)
            ->map(function ($row) {
                $views = (int)$row->views;
                $carts = (int)$row->carts;

                return [
                    'device' => $row->device,
                    'label' => (DeviceType::tryFrom($row->device) ?? DeviceType::Unknown)->label(),
                    'views' => $views,
                    'carts' => $carts,
                    'purchases' => (int)$row->purchases,
                    'visitors' => (int)$row->visitors,
                    'viewToCartRate' => $views > 0 ? round($carts / $views * 100, 1) : 0.0,
                    'cartToPurchaseRate' => $carts > 0 ? round((int)$row->purchases / $carts * 100, 1) : 0.0,
                ];
            })
            ->all();
    }

    public function searchLogDataTable()
    {
        return $this->searchLogRepository->dataTable();
    }

    private function topProducts(MarketingEventType $type, MarketingReportDto $dto)
    {
        return $this->marketingEventRepository->topProducts($type->value, $dto->from, $dto->to, $dto->limit);
    }
}
