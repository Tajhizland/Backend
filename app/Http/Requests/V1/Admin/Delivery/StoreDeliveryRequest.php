<?php

namespace App\Http\Requests\V1\Admin\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['nullable'],
            'status' => ['required', 'integer'],
            'price' => ['required', 'integer'],
             'logo' => ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
