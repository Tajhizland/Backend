<?php

namespace App\Http\Requests\Shop\News;

use Illuminate\Foundation\Http\FormRequest;

class FindNewsRequest extends FormRequest
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
