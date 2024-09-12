<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "product_id"=>["required","exists:App\Models\Product"],
            "option.*.item_id"=>["numeric","exists:App\Models\OptionItem,id"],
            "option.*.value"=>["string"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
