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
            'guaranty_time' => ['nullable'],
            'status' => ['required','int','in:1,0'],
            'categoryId' => ['required' ],
            'brand_id' => ['required','integer','exists:App\Models\Brand,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
