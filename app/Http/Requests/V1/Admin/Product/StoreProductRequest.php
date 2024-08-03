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
            'study' => ['nullable'],
            'categoryId' => ['required','integer'],
            'color.*.name' => ['required','string'],
            'color.*.code' => ['required','hex_color'],
            'color.*.status' => ['required','int','in:0,1'],
            'color.*.price' => ['required','int','min:0'],
            'color.*.stock' => ['required','int','min:0'],
            'color.*.discount' => ['nullable','int','min:0','max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
