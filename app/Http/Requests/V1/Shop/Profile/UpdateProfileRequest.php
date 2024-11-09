<?php

namespace App\Http\Requests\V1\Shop\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'email' => ['nullable','string' ,'email'],
            'gender' => ['numeric','in:0,1','nullable'],
            'city' => ['numeric', 'required'],
            'province' => ['numeric' ,'required'],
            'address' => ['string' ,'nullable'],
            'avatar' => ['image','mimes:jpeg,png,jpg,gif,svg,webp','nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
