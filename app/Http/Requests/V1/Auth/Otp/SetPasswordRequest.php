<?php

namespace App\Http\Requests\V1\Auth\Otp;

use Illuminate\Foundation\Http\FormRequest;

class SetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // اگر کاربر از قبل رمز دارد باید رمز فعلی را وارد کند؛ کاربر بدون رمز (ثبت‌نام با کد) نیازی ندارد
            'current_password' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
