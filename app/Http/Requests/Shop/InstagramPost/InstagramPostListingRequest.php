<?php

namespace App\Http\Requests\Shop\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class InstagramPostListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "filter" => ["nullable", "array"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
