<?php

namespace App\Http\Requests\V1\Shop\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'last_name' => ['required','string'],
            'national_code' => ['required','string','size:10'],
            'email' => ['nullable','string' ,'email'],
            'gender' => ['numeric','in:0,1','nullable'],
            'avatar' => ['image','mimes:jpeg,png,jpg,gif,svg,webp','nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
