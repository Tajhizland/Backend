<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', Rule::unique('coupons')->ignore($this->route('id'))],
            'user_id' => ['nullable' ],
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
