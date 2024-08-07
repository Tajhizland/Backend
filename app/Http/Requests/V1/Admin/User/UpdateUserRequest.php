<?php

namespace App\Http\Requests\V1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required','integer','exists:App\Models\User'],
            'name' => ['required','string'],
            'username' => ['required', 'string', 'regex:/^09\d{9}$/', 'unique:App\Models\User'],
            'role' => ['required', 'string', 'in:admin,user'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
