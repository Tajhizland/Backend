<?php

namespace App\Http\Requests\V1\Shop\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class SetDeliveryMethodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "delivery_id"=>['required','integer','exists:App\Models\Delivery:id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
