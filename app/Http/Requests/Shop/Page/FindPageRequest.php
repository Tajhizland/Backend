<?php

namespace App\Http\Requests\Shop\Page;

use Illuminate\Foundation\Http\FormRequest;

class FindPageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
