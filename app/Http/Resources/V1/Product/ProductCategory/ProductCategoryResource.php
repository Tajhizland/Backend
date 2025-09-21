<?php

namespace App\Http\Resources\V1\Product\ProductCategory;

use App\Http\Resources\V1\Comment\CommentCollection;
use App\Http\Resources\V1\GroupProduct\GroupProductCollection;
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
            'rating' => $this->getRatingAvg(),
            'colors' => new ProductColorCollection($this->activeProductColors),
            'images' => new ProductImageCollection($this->images),
            'groupItems' => new GroupProductCollection($this->groupItems),
            'comments' => new CommentCollection($this->confirmedComments),
            'productOptions' => new ProductOptionCollection($this->productOptions),

        ];
    }
}
