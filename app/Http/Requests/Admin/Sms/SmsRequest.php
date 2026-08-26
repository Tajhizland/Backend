<?php

namespace App\Http\Requests\Admin\Sms;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "message" => ["required", "string"],
            "userIds" => ["nullable"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
