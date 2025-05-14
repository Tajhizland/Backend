<?php

namespace App\Http\Requests\V1\Shop\ChatInfo;

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
