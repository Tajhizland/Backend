<?php

namespace App\Http\Resources\V1\CampaignBanner;

use App\Http\Resources\V1\Campaign\CampaignResource;
use App\Models\CampaignBanner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CampaignBanner */
class CampaignBannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'url' => $this->url,
            'type' => $this->type,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'campaign_id' => $this->campaign_id,

            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
        ];
    }
}
