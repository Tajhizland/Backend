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
            'status' => ['required','int','in:1,0'],
            'category_id' => ['required','integer','exists:App\Models\Category,id'],
            'brand_id' => ['required','integer','exists:App\Models\Brand,id'],
            'color.*.name' => ['required','string'],
            'color.*.code' => ['required'],
            'color.*.status' => ['required','int','in:0,1'],
            'color.*.price' => ['required','int','min:0'],
            'color.*.stock' => ['required','int','min:0'],
            'color.*.discount' => ['nullable','int','min:0','max:100'],
            'color.*.delivery_delay' => ['nullable','int' ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
