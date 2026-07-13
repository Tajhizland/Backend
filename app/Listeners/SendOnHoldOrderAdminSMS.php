<?php

namespace App\Listeners;

use App\Events\OrderRequestEvent;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Lang;

class SendOnHoldOrderAdminSMS
{
    public function __construct(
        private SmsServiceInterface $smsService
    )
    {
    }

    public function handle(OrderRequestEvent $event): void
    {
        $adminNumber = config("sms.admin_number");
        if (!$adminNumber) {
            return;
        }

        $this->smsService->send($adminNumber, Lang::get("sms.admin_on_hold_order", ["attr" => $event->onHoldOrder->order_id]));
    }
}
