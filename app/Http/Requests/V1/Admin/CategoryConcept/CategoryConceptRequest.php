<?php

namespace App\Http\Requests\V1\Admin\CategoryConcept;

use Illuminate\Foundation\Http\FormRequest;

class CategoryConceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'concept_id' => ['required', 'exists:concepts,id'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
