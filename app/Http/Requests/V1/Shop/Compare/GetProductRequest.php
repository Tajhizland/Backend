<?php

namespace App\Http\Requests\V1\Shop\Compare;

use Illuminate\Foundation\Http\FormRequest;

class GetProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "categoryIds" => ["required", "array"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
