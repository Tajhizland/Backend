<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Lang;

class SendOrderConfirmationSMS
{
    public function __construct(
        private SmsServiceInterface $smsService
    )
    {
    }

    public function handle(OrderPaidEvent $event): void
    {
        $this->smsService->send($event->order->orderInfo->mobile, Lang::get("sms.register_order", ["attr" => $event->order->id]));
    }
}
