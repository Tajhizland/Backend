<?php

namespace App\Http\Requests\V1\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'url' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
