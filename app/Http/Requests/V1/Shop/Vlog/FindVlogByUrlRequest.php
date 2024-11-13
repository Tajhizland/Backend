<?php

namespace App\Http\Requests\V1\Shop\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class FindVlogByUrlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "url" => ["required", "string"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
