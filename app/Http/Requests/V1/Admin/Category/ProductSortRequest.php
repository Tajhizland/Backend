<?php

namespace App\Http\Requests\V1\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class ProductSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            "product.*.id" => "required|numeric|exists:App\Models\Product,id",
            "product.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
