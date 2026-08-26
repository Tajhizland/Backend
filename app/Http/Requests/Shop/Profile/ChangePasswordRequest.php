<?php

namespace App\Http\Requests\Shop\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "current_password"=>"required",
            "new_password"=>"required|confirmed",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
