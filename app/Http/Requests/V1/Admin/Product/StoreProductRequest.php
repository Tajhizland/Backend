<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'url' => ['required','unique:App\Models\Product'],
            'description' => ['nullable'],
            'meta_description' => ['nullable'],
            'meta_title' => ['nullable'],
            'study' => ['nullable'],
            'guaranty_id' => ['nullable'],
            'status' => ['required','int','in:1,0'],
            'category_id' => ['required','integer','exists:App\Models\Category,id'],
            'brand_id' => ['required','integer','exists:App\Models\Brand,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
