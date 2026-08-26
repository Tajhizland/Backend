<?php

namespace App\Http\Requests\Shop\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class VlogListingRequest extends FormRequest
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
