<?php

namespace App\Http\Resources\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\MarketingEvent */
class CategoryStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'categoryId' => $this->category_id,
            'name' => $this->category?->name ?? 'دسته حذف شده',
            'url' => $this->category?->url,
            'total' => (int)$this->total,
            'visitors' => (int)$this->visitors,
            'lastEventAt' => $this->last_event_at
                ? Jalalian::fromDateTime($this->last_event_at)->format('Y/m/d H:i')
                : null,
        ];
    }
}
