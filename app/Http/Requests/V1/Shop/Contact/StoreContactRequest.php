<?php

namespace App\Http\Requests\V1\Shop\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'concept' => ['string'],
            'province_id' => ['required' ,'exists:App\Models\Province,id'],
            'city_id' => ['required' ,'exists:App\Models\City,id'],
            'mobile' => ['required' ],
            'message' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
