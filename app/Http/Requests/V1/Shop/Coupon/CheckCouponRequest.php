<?php

namespace App\Http\Requests\V1\Shop\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class CheckCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "code"=>["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
