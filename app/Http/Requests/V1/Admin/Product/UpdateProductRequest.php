<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required','integer','exists:App\Models\Product'],
            'name' => ['required'],
            'url' => ['required', Rule::unique('products')->ignore($this->id)],
            'description' => ['nullable'],
            'study' => ['nullable'],
            'meta_description' => ['nullable'],
            'meta_title' => ['nullable'],
            'brand_id' => ['required','integer','exists:App\Models\Brand,id'],
            'status' => ['required','int','in:1,0'],
            'categoryId' => ['required','integer' , 'exists:App\Models\Category,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
