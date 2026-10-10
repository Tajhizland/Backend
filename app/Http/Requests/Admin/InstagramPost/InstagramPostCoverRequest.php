<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class InstagramPostCoverRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cover' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
