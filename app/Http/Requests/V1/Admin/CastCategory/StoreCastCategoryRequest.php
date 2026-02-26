<?php

namespace App\Http\Requests\V1\Admin\CastCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreCastCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'icon' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
