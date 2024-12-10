<?php

namespace App\Http\Requests\V1\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "brand.*.id" => "required|numeric|exists:App\Models\Brand,id",
            "brand.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
