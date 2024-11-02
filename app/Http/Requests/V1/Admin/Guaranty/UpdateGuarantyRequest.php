<?php

namespace App\Http\Requests\V1\Admin\Guaranty;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuarantyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required','exists:App\Models\Guaranty'],
            'name' => ['required'],
            'description' => ['required'],
            'icon' => ['nullable'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
