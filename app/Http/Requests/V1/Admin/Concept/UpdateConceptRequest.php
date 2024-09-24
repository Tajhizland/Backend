<?php

namespace App\Http\Requests\V1\Admin\Concept;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'title' => ['required'],
            'description' => ['nullable'],
            'status' => ['required', 'integer'],
            'image' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
