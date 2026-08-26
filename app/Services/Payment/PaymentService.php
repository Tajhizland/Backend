<?php

namespace App\Services\Payment;

/**
 * نمای بیرونیِ جریان پرداخت.
 *
 * خودش منطقی ندارد و فقط هر متد را به سرویسِ مسئولش می‌دهد. وجودش برای این است
 * که PaymentServicesInterface و کنترلرها دست‌نخورده بمانند، در حالی که منطق در
 * سه سرویسِ کوچکِ مستقل زندگی می‌کند:
 *
 *   CheckoutPaymentService     ثبت سفارش جدید از روی سبد
 *   OnHoldPaymentService       پرداخت سفارش‌های معلقِ تاییدشده
 *   PaymentVerificationService بازگشت از درگاه و تایید تراکنش
 *
 * برای اضافه‌کردن یک درگاه جدید فقط PaymentGateway و PaymentGatewayRouter لمس
 * می‌شوند، نه این فایل.
 */
class PaymentService implements PaymentServicesInterface
{
    public function __construct(
        private CheckoutPaymentService     $checkoutPayment,
        private OnHoldPaymentService       $onHoldPayment,
        private PaymentVerificationService $verification,
    )
    {
    }

    public function request($userId, $useWallet, $shippingMethod, $code = null, $shippingPrice = 0, $gateway = 1)
    {
        return $this->checkoutPayment->request($userId, $useWallet, $shippingMethod, $code, $shippingPrice, $gateway);
    }

    public function verifyOrderByWallet($userId)
    {
        return $this->checkoutPayment->requestByWallet($userId);
    }

    public function onHoldOrderRequest($id, $userId)
    {
        return $this->onHoldPayment->request($id, $userId);
    }

    public function onHoldOrderVerifyByWallet($id, $userId)
    {
        return $this->onHoldPayment->payByWallet($id, $userId);
    }

    public function onHoldOrderCheckoutPayment($id, $userId, $useWallet, $shippingMethod, $code = null, $gateway = 1)
    {
        return $this->onHoldPayment->checkoutPayment($id, $userId, $useWallet, $shippingMethod, $code, $gateway);
    }

    /** بازگشت از درگاه بانکی (زیبال). */
    public function verifyPayment($request)
    {
        return $this->verification->verifyOnline($request);
    }

    /** بازگشت از دیجی‌پی. */
    public function verifyPayment2($request)
    {
        return $this->verification->verifyDigipay($request);
    }

    public function verifyPaymentSnapppay($request)
    {
        return $this->verification->verifySnappPay($request);
    }

    public function snappPayEligible($amount)
    {
        return $this->verification->snappPayEligible($amount);
    }
}
