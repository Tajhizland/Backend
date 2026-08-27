<?php

namespace App\Http\Resources\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * کارت ولاگ در صفحه اصلی (نسخه سبک VlogResource).
 *
 * @mixin \App\Models\Vlog
 */
class HomePageVlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "url" => $this->url,
            "video" => $this->video,
            "hls" => $this->hls,
            "poster" => $this->poster,
            "view" => $this->view,
            "author" => $this->user?->name ?? "",
            "created_at" => $this->created_at ? Jalalian::fromDateTime($this->created_at)->format('Y/m/d') : "",
        ];
    }
}
