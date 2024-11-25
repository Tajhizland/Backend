<?php

namespace App\Http\Requests\V1\Admin\Landing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
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
