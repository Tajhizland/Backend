<?php

namespace App\Http\Requests\V1\Admin\SpecialProduct;

use Illuminate\Foundation\Http\FormRequest;

class ShowHomepageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'homepage' => ['required','in:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
