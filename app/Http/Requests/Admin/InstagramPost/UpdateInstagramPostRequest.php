<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstagramPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'caption' => ['nullable', 'string'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
