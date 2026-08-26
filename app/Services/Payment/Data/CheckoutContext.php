<?php

namespace App\Services\Payment\Data;

use App\Enums\PaymentGateway;
use App\Models\Cart;
use App\Models\User;

/**
 * وضعیتِ جمع‌آوری‌شده‌ی یک چک‌اوت، پیش از آنکه تصمیم بگیریم سفارش با کدام
 * ترکیب کیف پول/درگاه ثبت شود.
 *
 * وجودش برای این است که سه سناریوی PaymentService::request() به‌جای اینکه هرکدام
 * ده خط مقدمه‌ی یکسان داشته باشند، فقط تفاوت‌شان را بنویسند.
 */
final class CheckoutContext
{
    /**
     * @param  mixed  $cartItems
     * @param  mixed  $address   آدرس فعال کاربر
     * @param  mixed  $delivery  روش ارسال انتخاب‌شده
     * @param  bool  $hasLimitedItem  سبد شامل کالای محدود است و سفارش باید تایید مدیر بخورد
     * @param  int|float  $itemsPrice       مبلغ اقلام (با تخفیف محصول)
     * @param  int|float  $payableAmount    مبلغ نهایی بعد از کسر کوپن
     * @param  int|float  $payableExtra     معادلِ دیجی‌پیِ payableAmount
     */
    public function __construct(
        public readonly User               $user,
        public readonly Cart               $cart,
        public readonly mixed              $cartItems,
        public readonly mixed              $address,
        public readonly mixed              $delivery,
        public readonly bool               $hasLimitedItem,
        public readonly mixed              $gateway,
        public readonly int|float          $itemsPrice,
        public readonly int|float          $payableAmount,
        public readonly int|float          $payableExtra,
        public readonly int                $maxDeliveryDelay,
        public readonly AppliedCoupon      $coupon,
    )
    {
    }

    public function isDigipay(): bool
    {
        return PaymentGateway::normalize($this->gateway) === PaymentGateway::DigiPay;
    }
}
