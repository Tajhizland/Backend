<?php

namespace App\Services\Marketing;

use App\DTOs\Marketing\MarketingReportDto;
use App\Enums\MarketingEventType;
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

    public function searchLogDataTable()
    {
        return $this->searchLogRepository->dataTable();
    }

    private function topProducts(MarketingEventType $type, MarketingReportDto $dto)
    {
        return $this->marketingEventRepository->topProducts($type->value, $dto->from, $dto->to, $dto->limit);
    }
}
