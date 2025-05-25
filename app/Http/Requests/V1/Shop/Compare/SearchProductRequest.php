<?php

namespace App\Http\Requests\V1\Shop\Compare;

use Illuminate\Foundation\Http\FormRequest;

class SearchProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "query" => ["required", "string"],
            "categoryIds" => ["required", "array"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
