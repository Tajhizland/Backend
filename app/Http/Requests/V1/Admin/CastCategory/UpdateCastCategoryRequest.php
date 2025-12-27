<?php

namespace App\Http\Requests\V1\Admin\CastCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCastCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:App\Models\CastCategory'],
            'name' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
