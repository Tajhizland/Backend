<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangePriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "percent" => "required|numeric|min:0|max:100",
            "action" => "required|string",
            "ids" => "array",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
