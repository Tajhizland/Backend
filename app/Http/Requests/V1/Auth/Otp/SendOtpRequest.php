<?php

namespace App\Http\Requests\V1\Auth\Otp;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // بدون unique : این کد هم برای کاربر جدید (ثبت‌نام) و هم کاربر موجود (ورود) استفاده می‌شود
            'mobile' => 'required|string|regex:/^09\d{9}$/',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
