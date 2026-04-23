<?php

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeDigipayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "ids" => "array",
            "digipay" => ["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
