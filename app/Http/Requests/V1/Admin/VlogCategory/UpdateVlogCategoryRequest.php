<?php

namespace App\Http\Requests\V1\Admin\VlogCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVlogCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'name' => ['required'],
            'url' => ['required', Rule::unique('url')->ignore($this->id)],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
