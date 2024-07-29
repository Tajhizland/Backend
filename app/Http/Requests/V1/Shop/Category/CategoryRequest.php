<?php

namespace App\Http\Requests\V1\Shop\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
