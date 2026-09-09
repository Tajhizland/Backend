<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Marketing\MarketingEventDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Marketing\TrackEventRequest;
use App\Services\Marketing\MarketingTrackerServiceInterface;

/**
 * ثبت رویدادهایی که فقط در مرورگر رخ می‌دهند (سبد خرید مهمان، مقایسه محصولات).
 * بقیه‌ی رویدادها سمت سرور و داخل همان سرویسی ثبت می‌شوند که کار اصلی را انجام می‌دهد.
 */
class MarketingEventController extends Controller
{
    public function __construct(
        private readonly MarketingTrackerServiceInterface $marketingTrackerService
    )
    {
    }

    public function track(TrackEventRequest $request)
    {
        $this->marketingTrackerService->trackFromClient(new MarketingEventDto(...$request->validated()));

        return $this->successResponse();
    }
}
