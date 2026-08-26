<?php

namespace App\Http\Requests\Shop\Cast;

use Illuminate\Foundation\Http\FormRequest;

class CastListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => ['nullable', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
