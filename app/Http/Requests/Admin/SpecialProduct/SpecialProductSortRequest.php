<?php

namespace App\Http\Requests\Admin\SpecialProduct;

use Illuminate\Foundation\Http\FormRequest;

class SpecialProductSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "special.*.id" => "required|numeric|exists:App\Models\Product,id",
            "special.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
