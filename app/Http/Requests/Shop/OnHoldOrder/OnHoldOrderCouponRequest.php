<?php

namespace App\Http\Requests\Shop\OnHoldOrder;

use Illuminate\Foundation\Http\FormRequest;

class OnHoldOrderCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
