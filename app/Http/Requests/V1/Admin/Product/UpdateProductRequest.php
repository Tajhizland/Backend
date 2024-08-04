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
            'status' => ['required','int','in:1,0'],
            'categoryId' => ['required','integer' , 'exists:App\Models\Category,id'],
            'color.*.id' => ['required','integer','exists:App\Models\ProductColor'],
            'color.*.name' => ['required','string'],
            'color.*.code' => ['required'],
            'color.*.status' => ['required','int','in:0,1'],
            'color.*.price' => ['required','int','min:0'],
            'color.*.stock' => ['required','int','min:0'],
            'color.*.discount' => ['required','int','min:0','max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
