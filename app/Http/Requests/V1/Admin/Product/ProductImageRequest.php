<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image'=> ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'product_id'=> ['required', 'exists:products,id'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
