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
            'last_name' => ['required','string'],
            'national_code' => ['required','string'],
            'name' => ['required','string'],
            'email' => ['nullable','string' ,'email'],
            'gender' => ['numeric','in:0,1','nullable'],
            'username' => ['required', 'string', 'regex:/^09\d{9}$/', Rule::unique('users')->ignore($this->id)],
            'role' => ['required', 'string', 'in:admin,user'],
            'role_id' => ['nullable' ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
