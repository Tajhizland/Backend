<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "categoryId" => ["nullable", "integer", "exists:categories,id"],
            "brandId" => ["nullable", "integer", "exists:brands,id"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
