<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FootprintRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "path" => ["required"],
            "user_id" => ["nullable"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
