<?php

namespace App\Http\Requests\Admin\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class VlogSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "query" => ["string", "required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
