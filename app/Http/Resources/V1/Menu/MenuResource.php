<?php

namespace App\Http\Resources\V1\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Menu */
class MenuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'title' => $this->title,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'parent' => new MenuResource($this->whenLoaded('parent')),
            'children' => new MenuCollection($this->whenLoaded('children')),
            'url' => $this->url,
            'banner_title' => $this->banner_title,
            'banner_link' => $this->banner_link,
            'banner_logo' => $this->banner_logo,
        ];
    }
}
