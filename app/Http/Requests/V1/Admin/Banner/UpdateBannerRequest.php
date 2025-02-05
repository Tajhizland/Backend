<?php

namespace App\Http\Requests\V1\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'url' => ['required'],
            'type' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
