<?php

namespace App\Http\Requests\V1\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required' ,'exists:App\Models\Brand'],
            'name' => ['required'],
            'url' => ['required' , Rule::unique('brands')->ignore($this->id)],
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
