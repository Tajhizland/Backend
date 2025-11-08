<?php

namespace App\Http\Requests\V1\Admin\Cast;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCastRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'audio' => ['required'],
            'vlog_id' => ['required', 'exists:vlogs'],
            'title' => ['required'],
            'description' => ['nullable'],
            'url' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
