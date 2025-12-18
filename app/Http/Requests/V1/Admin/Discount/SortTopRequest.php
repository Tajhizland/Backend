<?php

namespace App\Http\Requests\V1\Admin\Discount;

use Illuminate\Foundation\Http\FormRequest;

class SortTopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "discounts.*.id" => "required|numeric|exists:App\Models\DiscountItem,id",
            "discounts.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
