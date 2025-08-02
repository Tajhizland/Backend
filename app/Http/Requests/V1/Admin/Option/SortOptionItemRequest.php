<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class SortOptionItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "optionItem.*.id" => "required|numeric|exists:App\Models\OptionItem,id",
            "optionItem.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
