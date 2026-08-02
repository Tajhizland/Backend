<?php

namespace App\Http\Requests\V1\Admin\Upload;

use Illuminate\Foundation\Http\FormRequest;

class AbortUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:512'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
