<?php

namespace App\Http\Requests\V1\Admin\ProductGroup;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "groupId"=>["required","exists:App\Models\Product,id"],
            "productId"=>["required","exists:App\Models\Product,id"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
