<?php

namespace App\Http\Resources\Product\ProductCategory;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\ProductImage\ProductImageResource;
use App\Http\Resources\ProductOption\ProductOptionResource;
use App\Http\Resources\GroupProduct\GroupProductResource;
use App\Http\Resources\ProductColor\ProductColorResource;

class ProductCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'status' => $this->status,
            'is_stock' => $this->is_stock,
            'stock_of' => $this->stock_of,
            'testing_time' => $this->tesing_time,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
            'rating' => $this->getRatingAvg(),
            'allow_digipay' => $this->allow_digipay,
            'allow_snappay' => $this->allow_snappay,
            'colors' => ProductColorResource::collection($this->activeProductColors),
            'images' => ProductImageResource::collection($this->images),
            'groupItems' => GroupProductResource::collection($this->groupItems),
            'comments' => CommentResource::collection($this->confirmedComments),
            'productOptions' => ProductOptionResource::collection($this->productOptions),
            'stockOf' => new ProductResource($this->whenLoaded("stockOf")),
            'category_ids' => $this->productCategories->pluck('category_id'),

        ];
    }
}
