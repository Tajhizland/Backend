<?php

namespace App\Http\Requests\V1\Admin\Sms;

use Illuminate\Foundation\Http\FormRequest;

class SendToContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "message" => ["required", "string"],
            "mobiles" => ["nullable"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
