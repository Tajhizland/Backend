<?php

namespace App\Http\Requests\V1\Admin\PhoneBock;

use Illuminate\Foundation\Http\FormRequest;

class StorePhoneBockRequest extends FormRequest
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
