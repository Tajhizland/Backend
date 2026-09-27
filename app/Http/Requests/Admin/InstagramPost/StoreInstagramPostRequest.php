<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstagramPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'caption' => ['nullable', 'string'],
            'url' => ['required', 'string', 'max:256', 'unique:App\Models\InstagramPost,url'],
            'status' => ['required', 'integer'],
            'media' => ['required', 'array', 'min:1'],
            'media.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
