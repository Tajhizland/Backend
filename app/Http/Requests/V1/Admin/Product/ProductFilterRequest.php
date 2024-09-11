<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "product_id"=>["required"],
            "filter.*.id"=>["required","numeric","exists:App\Models\Filter"],
            "filter.*.item_id"=>["nullable","numeric"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
