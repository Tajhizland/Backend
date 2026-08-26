<?php

namespace App\Http\Requests\Admin\SpecialProduct;

use Illuminate\Foundation\Http\FormRequest;

class ShowHomepageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'homepage' => ['required','in:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
