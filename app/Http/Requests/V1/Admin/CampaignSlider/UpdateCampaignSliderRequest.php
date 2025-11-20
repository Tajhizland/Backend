<?php

namespace App\Http\Requests\V1\Admin\CampaignSlider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignSliderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'image' => ['required'],
            'url' => ['required'],
            'status' => ['required', 'integer'],
            'type' => ['required'],
            'sort' => ['nullable', 'integer'],
            'title' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
