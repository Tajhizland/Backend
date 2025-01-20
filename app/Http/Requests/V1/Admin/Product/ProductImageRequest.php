<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'image' => ['required', 'array'],
            'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'], // اعتبارسنجی هر فایل در آرایه
            'product_id'=> ['required', 'exists:products,id'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
