<?php

namespace App\Http\Requests\Admin\VlogCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVlogCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'url' => ['required', Rule::unique('vlog_categories')->ignore($this->route('id'))],
            'icon' => ['nullable'],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
