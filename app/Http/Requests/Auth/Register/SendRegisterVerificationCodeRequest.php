<?php

namespace App\Http\Requests\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;

class SendRegisterVerificationCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string|regex:/^09\d{9}$/|unique:users,username',
            ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
