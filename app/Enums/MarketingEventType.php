<?php

namespace App\Enums;

enum MarketingEventType: string
{
    case ProductView = 'product_view';
    case AddToCart = 'add_to_cart';
    case RemoveFromCart = 'remove_from_cart';
    case Compare = 'compare';
    case Favorite = 'favorite';
    case Purchase = 'purchase';

    public function label(): string
    {
        return match ($this) {
            static::ProductView => 'بازدید محصول',
            static::AddToCart => 'افزودن به سبد خرید',
            static::RemoveFromCart => 'حذف از سبد خرید',
            static::Compare => 'مقایسه محصول',
            static::Favorite => 'افزودن به علاقه‌مندی',
            static::Purchase => 'خرید',
        };
    }

    /**
     * رویدادهایی که فرانت مجاز است مستقیم ثبت کند.
     *
     * سبد مهمان و مقایسه در مرورگر انجام می‌شوند و به اندپوینت سروری نمی‌خورند، پس فقط
     * همین‌ها از بیرون قابل ثبت‌اند؛ بقیه (مثل خرید) باید از داخل سرویس‌ها ثبت شوند.
     */
    public static function clientTrackable(): array
    {
        return [
            static::ProductView->value,
            static::AddToCart->value,
            static::RemoveFromCart->value,
            static::Compare->value,
        ];
    }
}
