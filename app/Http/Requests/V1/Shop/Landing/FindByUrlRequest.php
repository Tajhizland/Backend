<?php

namespace App\Http\Requests\V1\Shop\Landing;

use Illuminate\Foundation\Http\FormRequest;

class FindByUrlRequest extends FormRequest
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
