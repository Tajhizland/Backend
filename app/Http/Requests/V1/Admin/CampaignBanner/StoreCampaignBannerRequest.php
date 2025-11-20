<?php

namespace App\Http\Requests\V1\Admin\CampaignBanner;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:App\Models\Campaign,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'url' => ['required'],
            'type' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
