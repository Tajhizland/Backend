<?php

namespace App\Http\Requests\Shop\OnHoldOrder;

use Illuminate\Foundation\Http\FormRequest;

class OnHoldOrderCheckoutPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "wallet" => "nullable|boolean",
            "shippingMethod" => "required|exists:deliveries,id",
            "code" => "nullable|string",
            // ۱ درگاه بانکی، ۳ دیجی‌پی، ۴ اسنپ‌پی
            "gateway" => "nullable|in:1,3,4",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
