<?php

namespace App\Http\Requests\V1\Admin\VlogCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreVlogCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'url' => ['required', 'unique:App\Models\VlogCategory'],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
