<?php

namespace App\Http\Requests\V1\Admin\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class StoreVlogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'url' => ['required'],
            'video' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
