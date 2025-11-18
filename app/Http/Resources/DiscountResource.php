<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\Campaign\CampaignResource;
use App\Http\Resources\V1\ProductColor\ProductColorResource;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Discount */
class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discount' => $this->discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product_color_id' => $this->product_color_id,
            'campaign_id' => $this->campaign_id,

            'productColor' => new ProductColorResource($this->whenLoaded('productColor')),
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
        ];
    }
}
