<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class InstagramPostSortMediaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "media.*.id" => "required|numeric|exists:App\Models\InstagramPostMedia,id",
            "media.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
