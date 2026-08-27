<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image:allow_svg', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'url' => ['required'],
            'type' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
