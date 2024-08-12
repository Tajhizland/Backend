<?php

namespace App\Http\Resources\V1\Product\SimpleProduct;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Comment\CommentResource;
use App\Http\Resources\V1\Price\PriceResource;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use App\Http\Resources\V1\Stock\StockResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class SimpleProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'status' => $this->status->label(),
            'view' => $this->view,
            'description' => $this->description,
            'category' => $this->categories->first()->name ?? "",
            'min_price' => $this->getMinColorPrice(),
            'rating' => $this->getRatingAvg(),
             'study' => $this->study,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'colors' => new ProductColorCollection($this->activeProductColors),
        ];
    }
}
