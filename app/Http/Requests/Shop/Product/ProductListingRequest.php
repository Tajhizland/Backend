<?php

namespace App\Http\Requests\Shop\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => ['nullable', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
