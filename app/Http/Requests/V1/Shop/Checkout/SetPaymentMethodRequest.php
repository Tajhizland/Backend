<?php

namespace App\Http\Requests\V1\Shop\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class SetPaymentMethodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "gateway_id"=>["required",'integer','exists:App\Models\Gateway:id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
