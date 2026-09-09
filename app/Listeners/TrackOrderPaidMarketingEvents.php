<?php

namespace App\Listeners;

use App\Enums\MarketingEventType;
use App\Events\OrderPaidEvent;
use App\Services\Marketing\MarketingTrackerServiceInterface;

/**
 * ثبت رویداد خرید برای هر قلم سفارش پرداخت‌شده.
 *
 * با همین رویداد است که گزارش «نرخ تبدیل» می‌تواند قیف کامل بازدید → سبد → خرید را نشان دهد،
 * و مشخص شود کدام محصول دیده می‌شود ولی به خرید نمی‌رسد.
 */
class TrackOrderPaidMarketingEvents
{
    public function __construct(
        private readonly MarketingTrackerServiceInterface $marketingTrackerService
    )
    {
    }

    public function handle(OrderPaidEvent $event): void
    {
        $order = $event->order->loadMissing('orderItems');

        foreach ($order->orderItems as $item) {
            $this->marketingTrackerService->track(MarketingEventType::Purchase, $item->product_id, [
                'user_id' => $order->user_id,
                'quantity' => $item->count,
                'meta' => [
                    'order_id' => $order->id,
                    'product_color_id' => $item->product_color_id,
                    'final_price' => $item->final_price,
                ],
            ]);
        }
    }
}
