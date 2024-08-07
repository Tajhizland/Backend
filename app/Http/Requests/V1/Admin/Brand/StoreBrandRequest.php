<?php

namespace App\Http\Requests\V1\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'url' => ['required'],
            'status' => ['required', 'integer'],
            'image' => ['nullable'],
            'description' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
