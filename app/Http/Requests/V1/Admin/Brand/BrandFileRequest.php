<?php

namespace App\Http\Requests\V1\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file'=> ['required', 'file'],
            'brand_id'=> ['required', 'exists:App\Models\Brand,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
