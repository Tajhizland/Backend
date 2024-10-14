<?php

namespace App\Http\Requests\V1\Admin\FileManager;

use Illuminate\Foundation\Http\FormRequest;

class FileManagerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['file','required'],
            'model_type' => ['required'],
            'model_id' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
