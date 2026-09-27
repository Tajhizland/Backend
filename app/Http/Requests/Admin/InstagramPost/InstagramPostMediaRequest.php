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
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
