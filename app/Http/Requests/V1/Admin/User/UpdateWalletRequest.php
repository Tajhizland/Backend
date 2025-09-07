<?php

namespace App\Http\Requests\V1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "user_id" => "required|exists:users,id",
            "wallet" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
