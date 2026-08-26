<?php

namespace App\Services\Order\Data;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\User;

/**
 * ورودیِ نام‌دارِ ساخت یک سفارش از روی سبد خرید.
 *
 * جایگزین ۱۵ پارامتر ترتیبیِ OrderRepository::createOrder() است. هدفش این است که
 * تفاوت سه سناریوی پرداخت (بدون کیف پول / کیف پول کامل / کیف پول جزئی) به‌جای اینکه
 * لای آرگومان‌های بی‌نام گم شود، در محل ساخت DTO صریح و قابل مقایسه باشد.
 *
 * مبالغ عمداً int|float هستند چون محاسبه‌ی درصدِ کوپن مقدار اعشاری تولید می‌کند و
 * رفتار فعلیِ ذخیره‌سازی نباید تغییر کند.
 */
final class OrderDraft
{
    /**
     * @param  mixed  $cartItems  اقلام سبد که به order_item تبدیل می‌شوند
     * @param  mixed  $address    آدرس فعال کاربر (گیرنده‌ی سفارش)
     * @param  int|float  $itemsPrice      مبلغ اقلام (ستون price)
     * @param  int|float  $deliveryPrice   هزینه‌ی ارسال
     * @param  int|float  $totalPrice      مبلغ کل سفارش
     * @param  int|float  $useWalletPrice  سهم کیف پول از مبلغ کل
     * @param  int|float  $finalPrice      مبلغ قابل پرداخت از درگاه
     * @param  int|float  $off             مبلغ تخفیف کوپن
     */
    public function __construct(
        public readonly User               $user,
        public readonly Cart               $cart,
        public readonly mixed              $cartItems,
        public readonly mixed              $address,
        public readonly OrderStatus        $status,
        public readonly mixed              $paymentMethod,
        public readonly mixed              $deliveryMethod,
        public readonly int|float          $itemsPrice,
        public readonly int|float          $deliveryPrice,
        public readonly int|float          $totalPrice,
        public readonly int|float          $useWalletPrice,
        public readonly int|float          $finalPrice,
        public readonly int|float          $off = 0,
        public readonly int                $deliveryDelayDays = 0,
        public readonly ?Coupon            $coupon = null,
        public readonly bool               $disableDiscount = false,
    )
    {
    }
}
