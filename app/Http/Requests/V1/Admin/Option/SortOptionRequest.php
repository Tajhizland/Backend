<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class SortOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "option.*.id" => "required|numeric|exists:App\Models\Option,id",
            "option.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
