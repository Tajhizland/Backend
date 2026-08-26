<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "ids" => "array",
            "stock"=>["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
