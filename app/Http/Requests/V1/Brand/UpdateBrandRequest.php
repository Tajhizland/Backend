<?php

namespace App\Http\Requests\V1\Brand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required' ,'exists:App\Models\Brand'],
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
