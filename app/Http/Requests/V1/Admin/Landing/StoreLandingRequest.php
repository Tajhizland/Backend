<?php

namespace App\Http\Requests\V1\Admin\Landing;

use Illuminate\Foundation\Http\FormRequest;

class StoreLandingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'status' => ['required', 'integer'],
            'url' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
