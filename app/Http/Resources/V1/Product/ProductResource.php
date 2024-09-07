<?php

namespace App\Http\Resources\V1\Product;

use App\Http\Resources\V1\Comment\CommentCollection;
use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        $isFavorite = false;
         if ($user)
            $isFavorite = $user->favorites()->where('product_id', $this->id)->exists();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'status' => $this->status,
            'view' => $this->view,
            'description' => $this->description,
            'category' => $this->categories->first()->name ?? "",
            'min_price' => $this->getMinColorPrice(),
            'rating' => $this->getRatingAvg(),
            'favorite' => $isFavorite,
            'study' => $this->study,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'colors' => new ProductColorCollection($this->activeProductColors),
            'comments' => new CommentCollection($this->confirmedComments),

        ];
    }
}
