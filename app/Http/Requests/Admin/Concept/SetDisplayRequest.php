<?php

namespace App\Http\Requests\Admin\Concept;

use Illuminate\Foundation\Http\FormRequest;

class SetDisplayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'=>['required','exists:App\Models\CategoryConcept,id'],
            'display'=>['required','string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
