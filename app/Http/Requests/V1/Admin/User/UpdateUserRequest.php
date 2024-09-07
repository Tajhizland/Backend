<?php

namespace App\Http\Requests\V1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required','integer','exists:App\Models\User'],
            'name' => ['required','string'],
            'username' => ['required', 'string', 'regex:/^09\d{9}$/', Rule::unique('username')->ignore($this->id)],
            'role' => ['required', 'string', 'in:admin,user'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
