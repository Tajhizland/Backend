<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductColorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id'=>['required','integer'],
            'color.*.id' => ['nullable','integer','exists:App\Models\ProductColor'],
            'color.*.name' => ['required','string'],
            'color.*.code' => ['required'],
            'color.*.status' => ['required','int','in:0,1,2'],
            'color.*.price' => ['required','int','min:0'],
            'color.*.stock' => ['required','int','min:0'],
            'color.*.discount' => ['required','int','min:0'],
            'color.*.discount_expire_time' => ['nullable'],
            'color.*.delivery_delay' => ['nullable','int' ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
