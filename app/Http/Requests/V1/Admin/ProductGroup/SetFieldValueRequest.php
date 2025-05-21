<?php

namespace App\Http\Requests\V1\Admin\ProductGroup;

use Illuminate\Foundation\Http\FormRequest;

class SetFieldValueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "groupProductId" => ["required", "exists:App\Models\GroupProduct,id"],
            "fieldId" => ["required", "exists:App\Models\GroupField,id"],
            "value" => ["required"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
