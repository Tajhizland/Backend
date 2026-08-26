<?php

namespace App\Http\Requests\Shop\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'string'],
            'filter' => ['nullable', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
