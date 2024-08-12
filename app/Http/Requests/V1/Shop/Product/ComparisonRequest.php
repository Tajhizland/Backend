<?php

namespace App\Http\Requests\V1\Shop\Product;

use Illuminate\Foundation\Http\FormRequest;

class ComparisonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => ['required', 'integer', 'exists:App\Models\Category:id'],
            "query" => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
