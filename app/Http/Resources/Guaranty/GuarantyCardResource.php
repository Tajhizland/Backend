<?php

namespace App\Http\Resources\Guaranty;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * نسخه سبک GuarantyResource (بدون description و تاریخ‌ها) برای کارت محصول.
 *
 * @mixin \App\Models\Guaranty
 */
class GuarantyCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "url" => $this->url,
            "icon" => $this->icon,
            "free" => $this->free,
        ];
    }
}
