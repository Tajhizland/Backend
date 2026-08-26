<?php

namespace App\Http\Requests\Shop\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class GetBlogByCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "url" => ["required"],
            "filter" => ["nullable", "array"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
