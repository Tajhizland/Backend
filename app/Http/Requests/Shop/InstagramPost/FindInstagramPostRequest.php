<?php

namespace App\Http\Requests\Shop\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class FindInstagramPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id" => ["required", "integer"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
