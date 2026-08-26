<?php

namespace App\Http\Resources\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use App\Http\Resources\Menu\MenuResource;

/** @mixin \App\Models\Menu */
class MenuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'id' => $this->id,
            'title' => $this->title,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'parent' => new MenuResource($this->whenLoaded('parent')),
            'children' => MenuResource::collection($this->whenLoaded('children')),
            'url' => $this->url,
            'category_id' => $this->category_id,
            'banner_link' => $this->banner_link,
            'banner_logo' => $this->banner_logo,
        ];
    }
}
