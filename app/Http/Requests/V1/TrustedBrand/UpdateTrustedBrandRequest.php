<?php

namespace App\Http\Requests\V1\TrustedBrand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrustedBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'logo' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
