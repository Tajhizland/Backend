<?php

namespace App\Http\Resources\Campaign;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use App\Http\Resources\CampaignSlider\CampaignSliderResource;
use App\Http\Resources\CampaignBanner\CampaignBannerResource;

/** @mixin Campaign */
class CampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo' => $this->logo,
            'discount_logo' => $this->discount_logo,
            'color' => $this->color,
            'background_color' => $this->background_color,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,

            'start_date_fa' => $this->start_date != null ? Jalalian::fromDateTime($this->start_date)->format('Y/m/d H:i') : "",
            'end_date_fa' => $this->end_date != null ? Jalalian::fromDateTime($this->end_date)->format('Y/m/d H:i') : "",
            'mobileSliders' => CampaignSliderResource::collection($this->whenLoaded('mobileSliders')),
            'desktopSliders' => CampaignSliderResource::collection($this->whenLoaded('desktopSliders')),

            'homepageBanner' => CampaignBannerResource::collection($this->whenLoaded('homepageBanner')),
            'homepage2Banner' => CampaignBannerResource::collection($this->whenLoaded('homepage2Banner')),

            'banner' => $this->banner,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
