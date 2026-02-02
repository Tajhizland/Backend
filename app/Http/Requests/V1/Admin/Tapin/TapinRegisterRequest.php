<?php

namespace App\Http\Requests\V1\Admin\Tapin;

use Illuminate\Foundation\Http\FormRequest;

class TapinRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "status"=>["required"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
