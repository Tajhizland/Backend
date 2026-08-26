<?php

namespace App\Http\Requests\TrustedBrand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrustedBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'logo' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
