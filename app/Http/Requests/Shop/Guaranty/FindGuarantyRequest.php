<?php

namespace App\Http\Requests\Shop\Guaranty;

use Illuminate\Foundation\Http\FormRequest;

class FindGuarantyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
