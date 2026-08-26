<?php

namespace App\Http\Requests\Admin\Discount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'discount' => ['required', 'array'],
            'discount.*.id' => ['required', 'exists:App\Models\DiscountItem,id'],
            'discount.*.discount_price' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
