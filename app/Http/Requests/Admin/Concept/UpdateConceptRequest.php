<?php

namespace App\Http\Requests\Admin\Concept;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'status' => ['required', 'integer'],
            'icon' => ['nullable' , 'image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
