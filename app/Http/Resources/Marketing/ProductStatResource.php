<?php

namespace App\Http\Resources\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * یک ردیف «محصول + تعداد رویداد» در گزارش‌های پربازدید/پرمقایسه/پرافزودن به سبد.
 *
 * @mixin \App\Models\MarketingEvent
 */
class ProductStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->product_id,
            'name' => $this->product?->name ?? 'محصول حذف شده',
            'url' => $this->product?->url,
            'image' => $this->product?->images?->first()?->url,
            'totalView' => $this->product?->view ?? 0,
            'total' => (int)$this->total,
            'visitors' => (int)$this->visitors,
            'lastEventAt' => $this->last_event_at
                ? Jalalian::fromDateTime($this->last_event_at)->format('Y/m/d H:i')
                : null,
        ];
    }
}
