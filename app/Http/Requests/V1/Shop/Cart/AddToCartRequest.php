<?php

namespace App\Http\Requests\V1\Shop\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "productColorId" => "required|integer",
            "count" => "integer|required",
            "guaranty_id" => "nullable|exists:App\Models\Guaranty,id",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
