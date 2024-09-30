<?php

namespace App\Http\Requests\V1\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required' ,'integer'],
            'name' => ['required'],
            'url' => ['required', Rule::unique('categories')->ignore($this->id)],
            'image' => ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
            'parent_id' => ['required', 'integer'],
            'description' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
