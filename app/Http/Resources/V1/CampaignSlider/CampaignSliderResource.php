<?php

namespace App\Http\Resources\V1\CampaignSlider;

use App\Http\Resources\V1\Campaign\CampaignResource;
use App\Models\CampaignSlider;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CampaignSlider */
class CampaignSliderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'url' => $this->url,
            'status' => $this->status,
            'type' => $this->type,
            'sort' => $this->sort,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'campaign_id' => $this->campaign_id,

            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
        ];
    }
}
