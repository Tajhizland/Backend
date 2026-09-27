<?php

namespace App\Http\Resources\InstagramPostMedia;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\InstagramPostMedia */
class InstagramPostMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'postId' => $this->instagram_post_id,
            'type' => $this->type,
            'file' => $this->file,
            'sort' => $this->sort,
        ];
    }
}
