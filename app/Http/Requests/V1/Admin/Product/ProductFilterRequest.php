<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "product_id"=>["required","exists:App\Models\Product"],
            "filter.*.id"=>["required","numeric","exists:App\Models\Filter"],
            "filter.*.item_id"=>["nullable","numeric","exists:App\Models\FilterItem"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
