<?php

namespace App\Http\Requests\V1\Admin\DiscountItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'discount_id' => ['required', 'exists:App\Models\Discount'],
            'product_color_id' => ['required', 'exists:App\Models\ProductColor'],
            'discount' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
