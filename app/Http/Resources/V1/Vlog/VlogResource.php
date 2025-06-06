<?php

namespace App\Http\Resources\V1\Vlog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Vlog */
class VlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'video' => $this->video,
            'hls' => $this->hls,
            'poster' => $this->poster,
            'status' => $this->status,
            'view' => $this->view,
            'author' => $this->user->name ??"",
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
