<?php

namespace App\Http\Requests\V1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class GetUserByTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "type" => ["required", "string"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
