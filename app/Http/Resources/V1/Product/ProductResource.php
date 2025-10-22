<?php

namespace App\Http\Resources\V1\Product;

use App\Http\Resources\V1\Brand\BrandResource;
use App\Http\Resources\V1\Comment\CommentCollection;
use App\Http\Resources\V1\GroupProduct\GroupProductCollection;
use App\Http\Resources\V1\Guaranty\GuarantyCollection;
use App\Http\Resources\V1\Guaranty\GuarantyResource;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use App\Http\Resources\V1\ProductImage\ProductImageCollection;
use App\Http\Resources\V1\ProductOption\ProductOptionCollection;
use App\Http\Resources\V1\ProductVideo\ProductVideoCollection;
use App\Http\Resources\V1\ProductVideo\ProductVideoResource;
use App\Http\Resources\V1\Vlog\VlogResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = Auth::guard('sanctum')->user();
        $isFavorite = false;
        if ($user)
            $isFavorite = $user->favorites()->where('product_id', $this->id)->exists();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'status' => $this->status,
            'view' => $this->view,
            'images_count' => $this->images_count,
            'description' => $this->description,
            'category' => $this->categories->first()->name ?? "",
            'category_ids' => $this->productCategories->pluck('category_id'),
            'category_id' => $this->categories->first()->id ?? "",
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand?->name,
            'guaranty_ids' => $this->productGuaranties->pluck('guaranty_id'),
            'guaranty_id' => $this->guaranty_id,
            'min_price' => $this->getMinColorPrice(),
            'rating' => $this->getRatingAvg(),
            'favorite' => $isFavorite,
            'study' => $this->study,
            'is_stock' => $this->is_stock,
            'type' => $this->type,
            'unboxing_video' => $this->unboxing_video,
            'intro_video' => $this->intro_video,
            'usage_video' => $this->usage_video,
            'meta_description' => $this->meta_description,
            'meta_title' => $this->meta_title,
            'unboxing' => new VlogResource($this->unboxing),
            'intro' => new VlogResource($this->intro),
            'usage' => new VlogResource($this->usage),
            'review' => $this->review,
            'stock_of' => $this->stock_of,
            'testing_time' => $this->tesing_time,
            'guaranty_time' => $this->guaranty_time,
            'guaranty' => new GuarantyResource($this->guaranty),
            'guaranties' => new GuarantyCollection($this->guaranties),
            'productOptions' => new ProductOptionCollection($this->productOptions),
            'colors' => new ProductColorCollection($this->activeProductColors),
            'comments' => new CommentCollection($this->confirmedComments),
            'images' => new ProductImageCollection($this->images),
            'brand' => new BrandResource($this->brand),
            'videos' => new ProductVideoCollection($this->videos),
            'groupItems' => new GroupProductCollection($this->groupItems),

            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
