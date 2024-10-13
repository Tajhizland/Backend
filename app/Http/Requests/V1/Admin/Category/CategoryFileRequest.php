<?php

namespace App\Http\Requests\V1\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file'=> ['required', 'file'],
            'category_id'=> ['required', 'exists:App\Models\Category,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
