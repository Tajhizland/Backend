<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class InstagramPostMediaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'postId' => ['required', "exists:App\Models\InstagramPost,id"],
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
