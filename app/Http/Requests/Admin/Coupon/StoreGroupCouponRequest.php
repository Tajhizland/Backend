<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "userIds" => ["required"],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date'],
            'status' => ['required', 'integer'],
            'price' => ['nullable', 'integer'],
            'percent' => ['nullable', 'integer'],
            'min_order_value' => ['nullable', 'integer'],
            'max_order_value' => ['nullable', 'integer'],
            'send_sms' => ['nullable', 'boolean'],
            'message' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
