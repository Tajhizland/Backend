<?php

namespace App\Http\Requests\V1\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "last_name" => "required|string",
            "national_code" => "nullable",
            'mobile' => 'required|string|regex:/^09\d{9}$/|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
