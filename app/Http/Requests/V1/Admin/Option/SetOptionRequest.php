<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class SetOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",

            "option.*.id" => "numeric|exists:option_items,id|nullable",
            "option.*.title" => "string|required",
            "option.*.status" => "numeric|in:0,1|required",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
