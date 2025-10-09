<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "categoryId" => ["required", "integer", "exists:categories,id"],
            "brandId" => ["required", "integer", "exists:brands,id"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
