<?php

namespace App\Services\Payment\Concerns;

/**
 * خروجی‌های استانداردِ جریان پرداخت که کنترلرها به فرانت پاس می‌دهند.
 */
trait BuildsPaymentResponse
{
    private const THANK_YOU_PAGE = "/thank_you_page";

    /** سفارش ثبت شد و منتظر تایید مدیر است. */
    private function limitRedirect(): array
    {
        return ["path" => self::THANK_YOU_PAGE, "type" => "limit"];
    }

    /** سفارش همین‌جا پرداخت شد (کیف پول). */
    private function paidRedirect(): array
    {
        return ["path" => self::THANK_YOU_PAGE, "type" => "paid"];
    }

    /** کاربر باید به درگاه هدایت شود. */
    private function paymentRedirect($path): array
    {
        return ["path" => $path, "type" => "payment"];
    }
}
