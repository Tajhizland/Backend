<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'type' => ['required','string','in:landing,listing'],
            'url' => ['required', Rule::unique('categories')->ignore($this->route('id'))],
            'image' => ['nullable' , 'image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
            'parent_id' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'description' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
