<?php

namespace App\Http\Requests\Admin\SpecialProduct;

use Illuminate\Foundation\Http\FormRequest;

class SpecialProductRequest extends FormRequest
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
