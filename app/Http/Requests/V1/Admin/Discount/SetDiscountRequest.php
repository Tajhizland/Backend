<?php

namespace App\Http\Requests\V1\Admin\Discount;

use Illuminate\Foundation\Http\FormRequest;

class SetDiscountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'discount_id' => ['required', 'exists:App\Models\Discount,id'],
            'discount' => ['required', 'array'],
            'discount.*.product_color_id' => ['required', 'exists:App\Models\ProductColor,id'],
            'discount.*.discount_price' => ['nullable', 'numeric'],
            'discount.*.discount_expire_time' => ['nullable'],
            'discount.*.top' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
