<?php

namespace App\Http\Resources\Product\ProductCategory;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\GroupProduct\GroupProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\ProductColor\ProductColorCollection;
use App\Http\Resources\ProductImage\ProductImageCollection;
use App\Http\Resources\ProductOption\ProductOptionCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'colors' => new ProductColorCollection($this->activeProductColors),
            'images' => new ProductImageCollection($this->images),
            'groupItems' => new GroupProductCollection($this->groupItems),
            'comments' => new CommentCollection($this->confirmedComments),
            'productOptions' => new ProductOptionCollection($this->productOptions),
            'stockOf' => new ProductResource($this->whenLoaded("stockOf")),
            'category_ids' => $this->productCategories->pluck('category_id'),

        ];
    }
}
