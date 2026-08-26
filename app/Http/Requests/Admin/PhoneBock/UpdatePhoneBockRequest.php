<?php

namespace App\Http\Requests\Admin\PhoneBock;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoneBockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required'],
            'name' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
