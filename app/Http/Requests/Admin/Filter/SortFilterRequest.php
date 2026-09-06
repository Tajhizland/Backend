<?php

namespace App\Http\Requests\Admin\Filter;

use Illuminate\Foundation\Http\FormRequest;

class SortFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "filter.*.id" => "required|numeric|exists:App\Models\Filter,id",
            "filter.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
