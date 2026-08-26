<?php

namespace App\Http\Requests\Shop\Product;

use Illuminate\Foundation\Http\FormRequest;

class FindProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
