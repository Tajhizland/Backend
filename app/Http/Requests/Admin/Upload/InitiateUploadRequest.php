<?php

namespace App\Http\Requests\Admin\Upload;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InitiateUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'profile' => ['required', 'string', Rule::in(array_keys(config('upload.profiles', [])))],
            'fileName' => ['required', 'string', 'max:255'],
            'size' => ['required', 'integer', 'min:1'],
            'mime' => ['nullable', 'string', 'max:128'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
