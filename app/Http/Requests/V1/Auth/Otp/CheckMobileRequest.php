<?php

namespace App\Http\Requests\V1\Auth\Otp;

use Illuminate\Foundation\Http\FormRequest;

class CheckMobileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|string|regex:/^09\d{9}$/',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
