<?php

namespace App\Http\Requests\V1\Admin\Discount;

use Illuminate\Foundation\Http\FormRequest;

class SortTopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "discount.*.id" => "required|numeric|exists:App\Models\DiscountItem,id",
            "discount.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
