<?php

namespace App\Http\Resources\Product\SimpleProduct;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Price\PriceResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Stock\StockResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use App\Http\Resources\ProductImage\ProductImageResource;
use App\Http\Resources\ProductColor\ProductColorResource;
use App\Http\Resources\ProductOption\ProductOptionResource;

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
            'category_ids' => $this->productCategories->pluck('category_id'),
            'study' => $this->study,
            'is_stock' => $this->is_stock,
            'stock_of' => $this->stock_of,
            'testing_time' => $this->tesing_time,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'colors' => ProductColorResource::collection($this->activeProductColors),
            'productOptions' => ProductOptionResource::collection($this->productOptions),
            'stockOf' => new ProductResource($this->whenLoaded("stockOf")),
            'images' => ProductImageResource::collection($this->images),
        ];
    }
}
