<?php

namespace App\Http\Requests\V1\Admin\Discount;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_color_id' => ['required', 'exists:App\Models\ProductColor,id'],
            'campaign_id' => ['required', 'exists:App\Models\Campaign,id'],
            'discount' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
