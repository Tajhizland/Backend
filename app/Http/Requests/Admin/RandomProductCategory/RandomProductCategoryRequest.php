<?php

namespace App\Http\Requests\Admin\RandomProductCategory;

use Illuminate\Foundation\Http\FormRequest;

class RandomProductCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
