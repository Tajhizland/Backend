<?php

namespace App\Http\Requests\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVerifyCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string|regex:/^09\d{9}$/|unique:users,username',
            'code' => 'required|string',
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
}
