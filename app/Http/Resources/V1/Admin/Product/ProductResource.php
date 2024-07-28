<?php

namespace App\Http\Resources\V1\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'status' => $this->status,
            'view' => $this->view,
            'category' => $this->categories->first()->name ?? "",
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
