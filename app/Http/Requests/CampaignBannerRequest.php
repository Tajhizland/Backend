<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:campaigns'],
            'image' => ['required'],
            'url' => ['required'],
            'type' => ['required'],
            'sort' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
