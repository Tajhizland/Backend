<?php

namespace App\Http\Requests\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'url' => ['required' , Rule::unique('brands')->ignore($this->route('id'))],
            'status' => ['required', 'integer'],
            'image' => ['nullable' , 'image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
            'banner' => ['nullable' , 'image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
            'description' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
