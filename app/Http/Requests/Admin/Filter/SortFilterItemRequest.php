<?php

namespace App\Http\Requests\Admin\Filter;

use Illuminate\Foundation\Http\FormRequest;

class SortFilterItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "filterItem.*.id" => "required|numeric|exists:App\Models\FilterItem,id",
            "filterItem.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
