<?php

namespace App\Http\Requests\V1\Admin\CampaignSlider;

use Illuminate\Foundation\Http\FormRequest;

class SortCampaignSliderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "slider.*.id" => "required|numeric|exists:App\Models\CampaignSlider,id",
            "slider.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
