<?php

namespace App\Http\Requests\V1\Auth\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordVerifyCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string|regex:/^09\d{9}$/',
            'code' => 'required|string',
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
}
