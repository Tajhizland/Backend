<?php

namespace App\Http\Requests\V1\Shop\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "productColorId"=>"required|integer",
            "guaranty_id" => "nullable|exists:App\Models\Guaranty",

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
