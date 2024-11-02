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
            'icon' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
