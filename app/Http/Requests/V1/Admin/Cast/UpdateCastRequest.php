<?php

namespace App\Http\Requests\V1\Admin\Cast;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCastRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'audio' => ['nullable'],
            'image' => ['nullable'],
            'vlog_id' => ['required', 'exists:App\Models\Vlog,id'],
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
