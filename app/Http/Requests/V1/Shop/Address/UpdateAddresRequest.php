<?php

namespace App\Http\Requests\V1\Shop\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddresRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['nullable'],
            'title' => ['required', 'string'],
            'city_id' => ['required', 'integer' , 'exists:App\Models\City,id'],
            'province_id' => ['required', 'integer','exists:App\Models\Province,id'],
            'tell' => ['nullable'],
            'mobile' => ['required','string','regex:/^09\d{9}$/'],
            'zip_code' => ['nullable'],
            'address' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
