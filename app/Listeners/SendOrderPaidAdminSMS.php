<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Lang;

class SendOrderPaidAdminSMS
{
    public function __construct(
        private SmsServiceInterface $smsService
    )
    {
    }

    public function handle(OrderPaidEvent $event): void
    {
        $adminNumber = config("sms.admin_number");
        if (!$adminNumber) {
            return;
        }

        $this->smsService->send($adminNumber, Lang::get("sms.admin_order_paid", ["attr" => $event->order->id]));
    }
}
