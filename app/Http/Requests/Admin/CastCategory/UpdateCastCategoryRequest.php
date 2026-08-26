<?php

namespace App\Http\Requests\Admin\CastCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCastCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'icon' => ['nullable'],
            'name' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
