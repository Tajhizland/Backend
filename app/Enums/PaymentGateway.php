<?php

namespace App\Enums;

/**
 * شناسه‌ی درگاه‌های پرداخت.
 *
 * مقادیر عمداً برابر با ستون gateways.id در دیتابیس‌اند و در orders.payment_method
 * ذخیره می‌شوند؛ پس تغییرشان داده‌ی موجود را می‌شکند.
 */
enum PaymentGateway: int
{
    case Online = 1;
    case Wallet = 2;
    case DigiPay = 3;
    case SnappPay = 4;

    public function label(): string
    {
        return match ($this) {
            self::Online => 'درگاه بانکی',
            self::Wallet => 'کیف پول',
            self::DigiPay => 'دیجی‌پی',
            self::SnappPay => 'اسنپ‌پی',
        };
    }

    /**
     * درگاه‌های اعتباری/اقساطی که مبلغ را خودشان تسویه می‌کنند و با کیف پول ترکیب نمی‌شوند.
     */
    public function isCreditProvider(): bool
    {
        return $this === self::DigiPay || $this === self::SnappPay;
    }

    /**
     * دیجی‌پی مبلغ را بدون تخفیفِ محصول و به‌علاوه‌ی درصد کارمزد می‌گیرد (extraPrice).
     */
    public function usesExtraPrice(): bool
    {
        return $this === self::DigiPay;
    }

    /**
     * تبدیل مقدار خام (int/string/null) به enum بدون از دست دادن مقادیر ناشناخته.
     * برای مقادیری که در enum نیستند null برمی‌گرداند تا رفتار قبلیِ else حفظ شود.
     */
    public static function normalize(mixed $value): ?self
    {
        if ($value instanceof self) {
            return $value;
        }
        return $value === null ? null : self::tryFrom((int)$value);
    }

    /**
     * مقدار عددی‌ای که باید در orders.payment_method بنشیند.
     */
    public static function toValue(mixed $value): ?int
    {
        if ($value instanceof self) {
            return $value->value;
        }
        return $value === null ? null : (int)$value;
    }
}
