<?php

namespace App\Http\Resources\V1\Product\ProductCategory;

use App\Http\Resources\V1\Comment\CommentCollection;
use App\Http\Resources\V1\GroupProduct\GroupProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use App\Http\Resources\V1\ProductImage\ProductImageCollection;
use App\Http\Resources\V1\ProductOption\ProductOptionCollection;
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
            'box_id' => $this->box_id,
            'weight' => $this->weight,
            'rating' => $this->getRatingAvg(),
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
