<?php

namespace App\Http\Resources\InstagramPost;

use App\Http\Resources\InstagramPostMedia\InstagramPostMediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\InstagramPost */
class InstagramPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'caption' => $this->caption,
            'url' => $this->url,
            'status' => $this->status,
            'view' => $this->view,
            'sort' => $this->sort,
            // در dataTable رسانه‌ها eager load نمی‌شوند؛ فقط تعدادشان لازم است
            'mediaCount' => $this->media_count ?? $this->whenLoaded('media', fn() => $this->media->count()),
            'media' => InstagramPostMediaResource::collection($this->whenLoaded('media')),
            // فید عمومی زمان را نسبی نشان می‌دهد («۳ روز پیش»)، پس به مبنای مطلق نیاز دارد
            'timestamp' => $this->created_at?->timestamp,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
