<?php

namespace App\Http\Requests\V1\Admin\PopularCategory;

use Illuminate\Foundation\Http\FormRequest;

class PopularCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:App\Models\Category,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
