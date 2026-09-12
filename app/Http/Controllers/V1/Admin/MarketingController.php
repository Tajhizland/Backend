<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Marketing\MarketingReportDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Marketing\MarketingReportRequest;
use App\Http\Resources\Marketing\CategoryStatResource;
use App\Http\Resources\Marketing\ProductEngagementResource;
use App\Http\Resources\Marketing\ProductStatResource;
use App\Http\Resources\Marketing\SearchLogResource;
use App\Services\Marketing\MarketingReportServiceInterface;

class MarketingController extends Controller
{
    public function __construct(
        private readonly MarketingReportServiceInterface $marketingReportService
    )
    {
    }

    public function overview(MarketingReportRequest $request)
    {
        return $this->dataResponse($this->marketingReportService->overview($this->dto($request)));
    }

    public function topViewedProducts(MarketingReportRequest $request)
    {
        return $this->dataResponse(ProductStatResource::collection(
            $this->marketingReportService->topViewedProducts($this->dto($request))
        ));
    }

    public function topCartProducts(MarketingReportRequest $request)
    {
        return $this->dataResponse(ProductStatResource::collection(
            $this->marketingReportService->topCartProducts($this->dto($request))
        ));
    }

    public function topComparedProducts(MarketingReportRequest $request)
    {
        return $this->dataResponse(ProductStatResource::collection(
            $this->marketingReportService->topComparedProducts($this->dto($request))
        ));
    }

    public function topFavoriteProducts(MarketingReportRequest $request)
    {
        return $this->dataResponse(ProductStatResource::collection(
            $this->marketingReportService->topFavoriteProducts($this->dto($request))
        ));
    }

    public function topCategories(MarketingReportRequest $request)
    {
        return $this->dataResponse(CategoryStatResource::collection(
            $this->marketingReportService->topCategories($this->dto($request))
        ));
    }

    public function topSearches(MarketingReportRequest $request)
    {
        return $this->dataResponse($this->marketingReportService->topSearches($this->dto($request)));
    }

    public function zeroResultSearches(MarketingReportRequest $request)
    {
        return $this->dataResponse($this->marketingReportService->zeroResultSearches($this->dto($request)));
    }

    public function conversionOpportunities(MarketingReportRequest $request)
    {
        return $this->dataResponse(ProductEngagementResource::collection(
            $this->marketingReportService->conversionOpportunities($this->dto($request))
        ));
    }

    public function unmetDemand(MarketingReportRequest $request)
    {
        return $this->dataResponse(ProductStatResource::collection(
            $this->marketingReportService->unmetDemand($this->dto($request))
        ));
    }

    public function devices(MarketingReportRequest $request)
    {
        return $this->dataResponse($this->marketingReportService->deviceReport($this->dto($request)));
    }

    public function searchLogDataTable()
    {
        return $this->dataResponseCollection(SearchLogResource::collection(
            $this->marketingReportService->searchLogDataTable()
        ));
    }

    private function dto(MarketingReportRequest $request): MarketingReportDto
    {
        return new MarketingReportDto(...$request->validated());
    }
}
