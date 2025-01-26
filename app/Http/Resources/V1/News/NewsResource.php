<?php

namespace App\Http\Resources\V1\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\News */
class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "url" => $this->url,
            "content" => $this->content,
            "img" => $this->img,
            "published" => $this->published,
            "static" => $this->static,
            'author' => $this->user->name??"" ,

            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d'),
        ];
    }
}
