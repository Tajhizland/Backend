<?php

namespace App\Services\Marketing;

use App\DTOs\Marketing\MarketingReportDto;

interface MarketingReportServiceInterface
{
    public function overview(MarketingReportDto $dto): array;

    public function topViewedProducts(MarketingReportDto $dto);

    public function topCartProducts(MarketingReportDto $dto);

    public function topComparedProducts(MarketingReportDto $dto);

    public function topFavoriteProducts(MarketingReportDto $dto);

    public function topCategories(MarketingReportDto $dto);

    public function topSearches(MarketingReportDto $dto);

    public function zeroResultSearches(MarketingReportDto $dto);

    public function conversionOpportunities(MarketingReportDto $dto);

    public function unmetDemand(MarketingReportDto $dto);

    public function deviceReport(MarketingReportDto $dto): array;

    public function searchLogDataTable();
}
