<?php

namespace App\Http\Requests\V1\Admin\DiscountItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:discount_items,id'],
            'discount_id' => ['required', 'exists:App\Models\Discount'],
            'product_color_id' => ['required', 'exists:App\Models\ProductColor'],
            'discount_price' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
