<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:App\Models\Product'],
            'name' => ['required'],
            'type' => ['required'],
            'url' => ['required', Rule::unique('products')->ignore($this->id)],
            'description' => ['nullable'],
            'study' => ['nullable'],
            'meta_description' => ['nullable'],
            'meta_title' => ['nullable'],
            'guaranty_id' => ['nullable'],
            'guaranty_time' => ['nullable'],
            'review' => ['nullable'],
            'brand_id' => ['nullable'],
            'status' => ['required', 'int', 'in:1,0'],
            'categoryId' => ['required'],
            'is_stock' => ['nullable'],
            'stock_of' => ['nullable'],
            'box_id' => ['nullable'],
            'weight' => ['nullable'],
            'testing_time' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
