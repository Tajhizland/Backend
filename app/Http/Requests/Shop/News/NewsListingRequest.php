<?php

namespace App\Http\Requests\Shop\News;

use Illuminate\Foundation\Http\FormRequest;

class NewsListingRequest extends FormRequest
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
