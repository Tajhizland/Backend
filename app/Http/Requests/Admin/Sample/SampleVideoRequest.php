<?php

namespace App\Http\Requests\Admin\Sample;

use Illuminate\Foundation\Http\FormRequest;

class SampleVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vlog_id' => ['required', 'exists:vlogs,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
