<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "categoryId" => ["nullable"],
            "brandId" => ["nullable"],
            "query" => ["nullable"],
            "discountId" => ["nullable"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
