<?php

namespace App\Http\Requests\V1\Shop\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "productColorId" => "required|integer",
            "count" => "integer|required"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
