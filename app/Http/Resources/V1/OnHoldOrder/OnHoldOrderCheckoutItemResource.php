<?php

namespace App\Http\Resources\V1\OnHoldOrder;

use App\Enums\ProductColorStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * یک قلم از سفارش معلق برای صفحه‌ی چک‌اوت.
 *
 * قیمت‌ها از مقادیر فریزشده‌ی order_item خوانده می‌شوند (نه قیمت روزِ محصول)
 * تا مبلغ سفارشی که مدیر تایید کرده تغییر نکند.
 *
 * @mixin \App\Models\OrderItem
 */
class OnHoldOrderCheckoutItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $productColor = $this->productColor;
        $stock = $productColor?->stock?->stock ?? 0;
        $hasStock = $productColor
            && $stock >= $this->count
            && $productColor->status != ProductColorStatus::DeActive->value;

        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product?->name,
                'allow_digipay' => $this->product?->allow_digipay,
                'allow_snappay' => $this->product?->allow_snappay,
                'url' => $this->product?->url,
                'digipay_extra_price' => $this->product?->digipay_extra_price,
                'image' => $this->product?->images?->first()?->url,
            ],
            'color' => [
                'id' => $this->product_color_id,
                'title' => $productColor?->color_name,
                'code' => $productColor?->color_code,
                'status' => $productColor?->status,
                'delivery_delay' => $productColor?->delivery_delay,
                // قیمت واحد در زمان ثبت سفارش
                'price' => (int)$this->price,
                // مبلغ تخفیف هر واحد؛ صفر یعنی بدون تخفیف
                'discount' => (int)$this->discount,
                'discountedPrice' => $this->discount > 0 ? (int)$this->price - (int)$this->discount : 0,
            ],
            'guaranty' => [
                'id' => $this->guaranty_id,
                'name' => $this->guaranty?->name,
                'free' => $this->guaranty?->free,
                // قیمت گارانتی هر واحد، فریزشده در زمان ثبت سفارش
                'price' => (int)$this->guaranty_price,
            ],
            'count' => $this->count,
            'hasStock' => (bool)$hasStock,
        ];
    }
}
