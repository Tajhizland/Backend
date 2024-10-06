<?php

namespace App\Http\Requests\V1\Admin\CategoryConcept;

use Illuminate\Foundation\Http\FormRequest;

class CategoryConceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'concept_id' => ['required', 'exists:concepts'],
            'category_id' => ['required', 'exists:categories'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
