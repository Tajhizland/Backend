<?php

namespace App\Http\Requests\V1\Shop\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'province_id' => ['required' ,'exists:App\Models\Province,id'],
            'city_id' => ['required' ,'exists:App\Models\City,id'],
            'email' => ['required', 'email', 'max:254'],
            'message' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
