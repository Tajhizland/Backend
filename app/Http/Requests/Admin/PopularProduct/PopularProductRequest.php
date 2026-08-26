<?php

namespace App\Http\Requests\Admin\PopularProduct;

use Illuminate\Foundation\Http\FormRequest;

class PopularProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
