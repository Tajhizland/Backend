<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "productColorId"=>"required|integer"

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
