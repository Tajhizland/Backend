<?php

namespace App\Http\Requests\V1\Admin\Sample;

use Illuminate\Foundation\Http\FormRequest;

class SampleImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required','file'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
