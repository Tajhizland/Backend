<?php

namespace App\Http\Requests\V1\Admin\Coupon;

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
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
