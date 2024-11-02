<?php

namespace App\Http\Requests\V1\Admin\Guaranty;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuarantyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
