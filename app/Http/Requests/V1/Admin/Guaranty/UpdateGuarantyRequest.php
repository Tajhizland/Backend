<?php

namespace App\Http\Requests\V1\Admin\Guaranty;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuarantyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:App\Models\Guaranty'],
            'name' => ['required'],
            'free' => ['required' ,'in:0,1' ,'numeric'],
            'description' => ['nullable','string'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
