<?php

namespace App\Http\Requests\Admin\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'logo' => ['required'],
            'background_color' => ['required'],
            'discount_logo' => ['required'],
            'color' => ['required'],
            'status' => ['required', 'integer'],
            'start_date' => ['nullable'],
            'end_date' => ['nullable'],
            'banner' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
