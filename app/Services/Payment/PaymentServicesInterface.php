<?php

namespace App\Services\Payment;

use App\DTOs\OnHoldOrder\OnHoldOrderCheckoutPaymentDto;
use App\DTOs\Payment\PaymentRequestDto;
use App\DTOs\Payment\SnappPayEligibleDto;

interface PaymentServicesInterface
{
    public function request(PaymentRequestDto $dto);

    public function verifyPayment2($request);

    public function verifyPaymentSnapppay($request);

    public function verifyPayment($request);

    public function onHoldOrderRequest($id, $userId);

    public function onHoldOrderVerifyByWallet($id, $userId);

    public function onHoldOrderCheckoutPayment(OnHoldOrderCheckoutPaymentDto $dto);

    public function verifyOrderByWallet($userId);

    public function snappPayEligible(SnappPayEligibleDto $dto);
}
