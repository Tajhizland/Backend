<?php

namespace App\Http\Requests\V1\Shop\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "wallet" => ["nullable", "bool"],
            "code" => ["nullable" ],
            "gateway" => ["nullable" ],
            "shippingMethod" => ["nullable"] ,
            "shippingPrice" => ["nullable"]

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
