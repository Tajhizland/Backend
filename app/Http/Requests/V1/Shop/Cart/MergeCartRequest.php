<?php

namespace App\Http\Requests\V1\Shop\Cart;

use Illuminate\Foundation\Http\FormRequest;

class MergeCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "items" => "required|array",
            "items.*.productColorId" => "required|integer",
            "items.*.count" => "required|integer|min:1",
            "items.*.guaranty_id" => "nullable|exists:App\Models\Guaranty,id",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
