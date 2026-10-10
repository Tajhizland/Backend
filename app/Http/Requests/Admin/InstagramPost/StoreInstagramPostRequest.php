<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstagramPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'caption' => ['nullable', 'string'],
            'status' => ['required', 'integer'],
            'media' => ['required', 'array', 'min:1'],
            'media.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime'],
            'covers' => ['sometimes', 'array'],
            'covers.*' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
