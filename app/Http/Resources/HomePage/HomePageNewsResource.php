<?php

namespace App\Http\Resources\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

/**
 * کارت مقاله در صفحه اصلی.
 *
 * content به جای متن کامل مقاله، یک excerpt متنی است؛ فرانت هم فقط
 * دو خط اول را (بعد از stripHTML) نشان می‌دهد.
 *
 * @mixin \App\Models\News
 */
class HomePageNewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "url" => $this->url,
            "img" => $this->img,
            "content" => $this->excerpt(),
            "author" => $this->user?->name ?? "",
            "created_at" => $this->created_at ? Jalalian::fromDateTime($this->created_at)->format('Y/m/d') : "",
        ];
    }

    private function excerpt(): string
    {
        $length = (int)config("settings.home_page.news_excerpt_length", 300);

        return Str::limit(trim(html_entity_decode(strip_tags((string)$this->content))), $length);
    }
}
