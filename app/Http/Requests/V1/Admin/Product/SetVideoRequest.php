<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SetVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "type"=>"required|string|in:unboxing,intro,usage",
            "productId"=>"required|numeric",
            "file"=>"required|file",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
