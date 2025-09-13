<?php

namespace App\Http\Requests\V1\Admin\Dictionary;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDictionaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'original_word' => ['required'],
            'mean' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
