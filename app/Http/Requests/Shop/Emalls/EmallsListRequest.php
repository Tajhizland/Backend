<?php

namespace App\Http\Requests\Shop\Emalls;

use Illuminate\Foundation\Http\FormRequest;

class EmallsListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'item_per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
