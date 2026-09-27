<?php

namespace App\Http\Requests\Shop\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class FindInstagramPostByUrlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "url" => ["required", "string"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
