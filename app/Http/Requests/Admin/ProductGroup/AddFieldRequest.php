<?php

namespace App\Http\Requests\Admin\ProductGroup;

use Illuminate\Foundation\Http\FormRequest;

class AddFieldRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "groupId" => ["required", "exists:App\Models\Product,id"],
            "title" => ["required", "string"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
