<?php

namespace App\Http\Requests\Admin\VlogCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreVlogCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'url' => ['required', 'unique:App\Models\VlogCategory'],
            'status' => ['required'],
            'icon' => ['nullable','image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
