<?php

namespace App\Http\Requests\V1\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "banner.*.id" => "required|numeric|exists:App\Models\CampaignBanner,id",
            "banner.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
