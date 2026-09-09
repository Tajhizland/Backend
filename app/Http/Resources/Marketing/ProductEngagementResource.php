<?php

namespace App\Http\Resources\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * قیف تعامل هر محصول: بازدید → سبد → خرید، به همراه نرخ تبدیل.
 *
 * @mixin \App\Models\MarketingEvent
 */
class ProductEngagementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $views = (int)$this->views;
        $carts = (int)$this->carts;

        return [
            'productId' => $this->product_id,
            'name' => $this->product?->name ?? 'محصول حذف شده',
            'url' => $this->product?->url,
            'image' => $this->product?->images?->first()?->url,
            'views' => $views,
            'carts' => $carts,
            'compares' => (int)$this->compares,
            'favorites' => (int)$this->favorites,
            'purchases' => (int)$this->purchases,
            'viewToCartRate' => $views > 0 ? round($carts / $views * 100, 1) : 0.0,
            'cartToPurchaseRate' => $carts > 0 ? round((int)$this->purchases / $carts * 100, 1) : 0.0,
        ];
    }
}
