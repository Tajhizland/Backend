<?php

namespace App\Services\Payment;

use App\Enums\PaymentGateway;
use App\Models\Order;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Services\DigiPay\DigiPayService;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;
use App\Services\SnappPay\SnappPayService;

/**
 * تنها جایی که تصمیم گرفته می‌شود درخواست پرداخت به کدام درگاه برود.
 *
 * قبلاً زنجیره‌ی if ($gateway == 3) ... elseif ($gateway == 4) ... else در سه نقطه‌ی
 * PaymentService تکرار شده بود.
 */
class PaymentGatewayRouter
{
    private $online;

    public function __construct(
        private GatewayStrategyServicesInterface $gatewayStrategyServices,
        private DigiPayService                   $digiPayService,
        private SnappPayService                  $snappPayService,
        private OrderItemRepositoryInterface     $orderItemRepository,
    )
    {
        $this->online = $this->gatewayStrategyServices->strategy();
    }

    /**
     * درگاه بانکیِ فعال (استراتژی پیش‌فرض) برای مراحل callback و verify.
     */
    public function onlineGateway()
    {
        return $this->online;
    }

    /**
     * مبلغ به تومان گرفته می‌شود و به ریال (×۱۰) به درگاه فرستاده می‌شود.
     *
     * @param  int|float  $amountToman  مبلغی که باید از درگاه گرفته شود
     * @param  mixed  $mobile      شماره‌ی موبایل مورد نیاز دیجی‌پی
     * @param  mixed  $orderItems  اگر از قبل لود شده باشد پاس داده می‌شود تا کوئری اضافه نخورد
     * @return mixed آدرس هدایت کاربر به درگاه
     */
    public function request(mixed $gateway, Order $order, int|float $amountToman, $mobile = null, $orderItems = null)
    {
        $rial = $amountToman * 10;

        return match (PaymentGateway::normalize($gateway)) {
            PaymentGateway::DigiPay => $this->digiPayService->request($rial, $mobile, $order->id, $this->items($order, $orderItems)),
            PaymentGateway::SnappPay => $this->snappPayService->request($order->id, $this->items($order, $orderItems), $rial),
            default => $this->online->request($rial, $order->id),
        };
    }

    private function items(Order $order, $orderItems)
    {
        return $orderItems ?? $this->orderItemRepository->getByOrderId($order->id);
    }
}
