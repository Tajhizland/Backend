<?php

namespace App\Http\Requests\V1\Admin\HomepageCategory;

use Illuminate\Foundation\Http\FormRequest;

class HomepageCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
