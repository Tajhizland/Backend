<?php

namespace App\Services\Payment;

use App\Events\OrderPaidEvent;
use App\Exceptions\BreakException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\DigiPay\DigiPayService;
use App\Services\Order\OrderPaymentFinalizerInterface;
use App\Services\SnappPay\SnappPayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * بازگشت کاربر از درگاه: تایید تراکنش و نهایی‌کردن سفارش.
 *
 * در هر سه مسیر، نوشتن‌های دیتابیس داخل تراکنش‌اند و OrderPaidEvent عمداً بعد از
 * commit شلیک می‌شود؛ چون listenerهایش پیامک و نوتیفیکیشن می‌فرستند و نباید در
 * صورت rollback پیام رفته باشد.
 */
readonly class PaymentVerificationService
{
    public function __construct(
        private OrderRepositoryInterface       $orderRepository,
        private DigiPayService                 $digiPayService,
        private SnappPayService                $snappPayService,
        private OrderPaymentFinalizerInterface $orderPaymentFinalizer,
        private PaymentGatewayRouter           $gatewayRouter,
    )
    {
    }

    public function verifyOnline($request)
    {
        $gateway = $this->gatewayRouter->onlineGateway();
        $callback = $gateway->callbackParams($request);
        $gateway->verify($callback->trackId);

        return $this->complete($callback->orderId, $callback->trackId);
    }

    public function verifyDigipay($request)
    {
        $callback = $this->digiPayService->callbackParams($request);
        $this->digiPayService->verify($callback->trackId, $callback->orderId);

        return $this->complete($callback->orderId, $callback->trackId);
    }

    public function verifySnappPay($request)
    {
        try {
            DB::beginTransaction();

            $callback = $this->snappPayService->callbackParams($request);
            $order = $this->orderRepository->findOrFail($callback->orderId);

            $verify = $this->snappPayService->verify($order->payment_token);
            if ($verify["successful"] != true) {
                throw new BreakException("پرداخت ناموفق بود");
            }

            $this->snappPayService->settle($order->payment_token);

            // اسنپ‌پی کل مبلغ را خودش تسویه می‌کند، پس چیزی از کیف پول کم نمی‌شود
            $this->orderPaymentFinalizer->applyPayment($order, 0, $verify["response"]["transactionId"]);

            DB::commit();
            event(new OrderPaidEvent($order));

            return $callback->orderId;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function snappPayEligible($amount)
    {
        // مبلغ از فرانت به تومان می‌آید و اسنپ‌پی مبلغ را به ریال می‌خواهد (× ۱۰)
        return $this->snappPayService->eligible($amount * 10);
    }

    /**
     * مشترکِ بازگشت موفق از زیبال و دیجی‌پی.
     */
    private function complete($orderId, $trackId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        DB::transaction(function () use ($order, $trackId) {
            $this->orderPaymentFinalizer->applyPayment($order, $order->use_wallet_price, $trackId);
        });

        event(new OrderPaidEvent($order));

        return 1;
    }
}
