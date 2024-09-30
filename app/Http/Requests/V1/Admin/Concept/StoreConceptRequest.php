<?php

namespace App\Http\Requests\V1\Admin\Concept;

use Illuminate\Foundation\Http\FormRequest;

class StoreConceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'status' => ['required', 'integer'],
            'image' => ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
