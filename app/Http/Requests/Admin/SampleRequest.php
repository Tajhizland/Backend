<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SampleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
