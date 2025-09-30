<?php

namespace App\Http\Requests\V1\Admin\Sms;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "type" => ["required", "string"],
            "message" => ["required", "string"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
