<?php

namespace App\Http\Requests\Admin\Cast;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCastRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio' => ['nullable'],
            'image' => ['nullable'],
            'vlog_id' => ['required', 'exists:App\Models\Vlog,id'],
            'category_id' => ['required', 'exists:App\Models\CastCategory,id'],
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
