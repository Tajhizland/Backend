<?php

namespace App\Http\Resources\V1\Breadcrumb;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class BreadcrumbResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $url = $this->type == "listing" ? "category/" . $this->url : "landing/" . $this->url;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $url,
        ];
    }
}
