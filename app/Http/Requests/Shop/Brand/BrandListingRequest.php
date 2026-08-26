<?php

namespace App\Http\Requests\Shop\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandListingRequest extends FormRequest
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
