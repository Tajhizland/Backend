<?php

namespace App\Http\Resources\V1\Vlog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'status' => $this->status,
            'view' => $this->view,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
