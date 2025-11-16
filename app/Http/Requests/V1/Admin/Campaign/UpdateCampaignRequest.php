<?php

namespace App\Http\Requests\V1\Admin\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', "exists:App\Models\Campaign"],
            'title' => ['required'],
            'logo' => ['required'],
            'color' => ['required'],
            'status' => ['required', 'integer'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'banner' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
