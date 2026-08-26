<?php

namespace App\Http\Requests\Shop\ChatInfo;

use Illuminate\Foundation\Http\FormRequest;

class ChatInfoSyncRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "token" => ["required", "string"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
