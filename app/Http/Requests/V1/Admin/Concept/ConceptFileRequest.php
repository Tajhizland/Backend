<?php

namespace App\Http\Requests\V1\Admin\Concept;

use Illuminate\Foundation\Http\FormRequest;

class ConceptFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file'=> ['required', 'file'],
            'concept_id'=> ['required', 'exists:App\Models\Concept,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
