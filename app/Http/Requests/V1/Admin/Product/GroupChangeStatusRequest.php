<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "ids" => "array",
            "status" => ["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
