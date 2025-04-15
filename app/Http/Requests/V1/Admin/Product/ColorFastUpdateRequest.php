<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ColorFastUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'color.*.id' => ['nullable','integer','exists:App\Models\ProductColor'],
            'color.*.price' => ['required','int','min:0'],
            'color.*.discount' => ['required','int','min:0'],
            'color.*.status' => ['required','int','in:0,1,2'],
            'color.*.stock' => ['required','int','min:0'],
            'color.*.delivery_delay' => ['required','int','min:0'],
            'color.*.discount_expire_time' => ['nullable' ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
