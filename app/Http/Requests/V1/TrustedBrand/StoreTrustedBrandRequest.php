<?php

namespace App\Http\Requests\V1\TrustedBrand;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrustedBrandRequest extends FormRequest
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
