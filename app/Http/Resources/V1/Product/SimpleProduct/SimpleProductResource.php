<?php

namespace App\Http\Resources\V1\Product\SimpleProduct;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Comment\CommentResource;
use App\Http\Resources\V1\Price\PriceResource;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use App\Http\Resources\V1\Stock\StockResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

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
             'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'colors' => new ProductColorCollection($this->activeProductColors),
        ];
    }
}
