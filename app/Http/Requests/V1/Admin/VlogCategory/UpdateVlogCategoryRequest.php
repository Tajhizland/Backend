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
            'url' => ['required', Rule::unique('vlog_categories')->ignore($this->id)],
            'icon' => ['nullable'],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
