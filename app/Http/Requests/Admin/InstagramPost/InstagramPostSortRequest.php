<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class InstagramPostSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "post.*.id" => "required|numeric|exists:App\Models\InstagramPost,id",
            "post.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
