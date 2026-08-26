<?php

namespace App\Http\Requests\Admin\Upload;

use Illuminate\Foundation\Http\FormRequest;

class SignPartsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:512'],
            'partNumbers' => ['required', 'array', 'min:1', 'max:' . config('upload.sign_batch_size', 50)],
            'partNumbers.*' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
