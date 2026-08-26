<?php

namespace App\Http\Requests\Shop\Torob;

use Illuminate\Foundation\Http\FormRequest;

class TorobListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page_urls' => ['nullable'],
            'page_uniques' => ['nullable'],
            'page' => ['nullable'],
            'sort' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
