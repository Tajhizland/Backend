<?php

namespace App\Http\Requests\V1\Shop\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddresRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city_id' => ['required', 'integer' , 'exists:App\Models\City'],
            'province_id' => ['required', 'integer','exists:App\Models\Province'],
            'tell_code' => ['required'],
            'tell' => ['required'],
            'mobile' => ['required','string','regex:/^09\d{9}$/'],
            'zip_code' => ['required'],
            'address' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
